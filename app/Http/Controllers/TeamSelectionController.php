<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeamSelectionController extends Controller
{
    /**
     * Muestra el formulario para seleccionar competición y equipo
     */
    public function showSelectionForm()
    {
        // Obtener la temporada actual (o la más reciente)
        $currentSeason = DB::table('seasons')
            ->where('is_current', true)
            ->orWhere('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->first();
            
        if (!$currentSeason) {
            return back()->with('error', 'No hay una temporada activa configurada.');
        }
        
        // Obtener todas las competiciones disponibles en la temporada actual
        $leagues = DB::table('leagues')
            ->select('leagues.*')
            ->join('league_team', 'leagues.id', '=', 'league_team.league_id')
            ->where('league_team.season_id', $currentSeason->id)
            ->distinct()
            ->orderBy('leagues.name')
            ->get();
            
        return view('team-selection', compact('leagues', 'currentSeason'));
    }
    
    /**
     * Obtiene los equipos de una competición específica en la temporada actual
     */
    public function getTeams($leagueId)
    {
        $currentSeason = DB::table('seasons')
            ->where('is_current', true)
            ->orWhere('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->first();
            
        if (!$currentSeason) {
            return response()->json(['error' => 'No hay temporada activa'], 404);
        }
        
        $teams = DB::table('teams')
            ->select('teams.*')
            ->join('league_team', 'teams.id', '=', 'league_team.team_id')
            ->where('league_team.league_id', $leagueId)
            ->where('league_team.season_id', $currentSeason->id)
            ->orderBy('teams.name')
            ->get();
            
        return response()->json($teams);
    }
    
    /**
     * Establece el equipo seleccionado en la sesión
     */
    public function selectTeam(Request $request)
    {
        // Log detallado de la solicitud
        \Log::info('=== SOLICITUD DE SELECCIÓN DE EQUIPO ===');
        \Log::info('Usuario ID: ' . auth()->id());
        \Log::info('Usuario: ' . (auth()->user() ? auth()->user()->name : 'No autenticado'));
        \Log::info('Datos recibidos:', [
            'team_id' => $request->team_id,
            'league_id' => $request->league_id,
            'all_input' => $request->all()
        ]);
        \Log::info('=======================================');

        // Validar los datos de entrada
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_id' => 'required|exists:leagues,id',
        ]);
        
        \Log::info('=== VALIDACIÓN EXITOSA ===');
        \Log::info('Datos validados:', $validated);
        
        // Obtener la temporada actual
        $currentSeason = DB::table('seasons')
            ->where('is_current', true)
            ->orWhere('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->first();
            
        if (!$currentSeason) {
            \Log::error('No se encontró una temporada activa');
            return back()->with('error', 'No se encontró una temporada activa.');
        }
        
        // Verificar que el equipo pertenece a la liga en la temporada actual
        $isValidTeam = DB::table('league_team')
            ->where('league_id', $request->league_id)
            ->where('team_id', $request->team_id)
            ->where('season_id', $currentSeason->id)
            ->exists();
            
        if (!$isValidTeam) {
            \Log::error('El equipo no pertenece a la liga en la temporada actual', [
                'team_id' => $request->team_id,
                'league_id' => $request->league_id,
                'season_id' => $currentSeason->id
            ]);
            return back()->with('error', 'El equipo seleccionado no pertenece a esta competición en la temporada actual.');
        }
        
        // Guardar el ID del equipo y la liga en la sesión
        session([
            'selected_team_id' => $request->team_id,
            'selected_league_id' => $request->league_id,
            'selected_season_id' => $currentSeason->id
        ]);
        
        // Guardar la relación en la tabla user_teams
        $userId = auth()->id();
        
        if (!$userId) {
            \Log::error('Usuario no autenticado al intentar seleccionar equipo');
            return back()->with('error', 'Debes iniciar sesión para seleccionar un equipo.');
        }

        // Primero, eliminar cualquier relación existente para este usuario en esta temporada
        try {
            DB::beginTransaction();
            
            // Registrar datos antes de la operación
            \Log::info('=== INICIANDO TRANSACCIÓN ===');
            \Log::info('Eliminando relaciones existentes para el usuario en esta temporada', [
                'user_id' => $userId,
                'season_id' => $currentSeason->id
            ]);
            
            // Eliminar relaciones existentes para este usuario en esta temporada
            $deleted = DB::table('user_teams')
                ->where('user_id', $userId)
                ->where('season_id', $currentSeason->id)
                ->delete();
                
            \Log::info('Relaciones eliminadas:', ['count' => $deleted]);
            
            // Crear la nueva relación
            $userTeamData = [
                'user_id' => $userId,
                'team_id' => $request->team_id,
                'season_id' => $currentSeason->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            \Log::info('Intentando insertar nueva relación:', $userTeamData);
            
            $inserted = DB::table('user_teams')->insert($userTeamData);
            
            if (!$inserted) {
                throw new \Exception('No se pudo insertar la relación en la base de datos');
            }
            
            DB::commit();
            
            \Log::info('=== TRANSACCIÓN COMPLETADA CON ÉXITO ===');
            \Log::info('Relación de equipo actualizada', [
                'user_id' => $userId,
                'team_id' => $request->team_id,
                'season_id' => $currentSeason->id,
                'inserted' => $inserted
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al guardar la relación de equipo', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'team_id' => $request->team_id,
                'season_id' => $currentSeason->id
            ]);
            
            return back()->with('error', 'Error al guardar la selección del equipo. Por favor, inténtalo de nuevo.');
        }    
        
        // Obtener el nombre del equipo y la competición para mostrarlo en el mensaje
        $team = DB::table('teams')
            ->where('id', $request->team_id)
            ->first();
            
        $league = DB::table('leagues')
            ->where('id', $request->league_id)
            ->first();
            
        // Forzar la regeneración de la sesión
        $request->session()->regenerate();
        
        // Agregar información de depuración
        \Log::info('=== REDIRECCIÓN AL DASHBOARD ===');
        \Log::info('Usuario ID: ' . auth()->id());
        \Log::info('Equipo ID: ' . $request->team_id);
        \Log::info('Liga ID: ' . $request->league_id);
        \Log::info('Sesión:', [
            'selected_team_id' => session('selected_team_id'),
            'selected_league_id' => session('selected_league_id'),
            'selected_season_id' => session('selected_season_id')
        ]);
        \Log::info('================================');
            
        return redirect()->route('dashboard')
            ->with([
                'status' => 'team-selected',
                'message' => "¡Bienvenido al equipo {$team->name} en la competición {$league->name}!"
            ]);
    }
}
