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
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_id' => 'required|exists:leagues,id',
        ]);
        
        // Verificar que el equipo pertenece a la liga en la temporada actual
        $currentSeason = DB::table('seasons')
            ->where('is_current', true)
            ->orWhere('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->first();
            
        $isValidTeam = DB::table('league_team')
            ->where('league_id', $request->league_id)
            ->where('team_id', $request->team_id)
            ->where('season_id', $currentSeason->id)
            ->exists();
            
        if (!$isValidTeam) {
            return back()->with('error', 'El equipo seleccionado no pertenece a esta competición.');
        }
        
        // Guardar el ID del equipo y la liga en la sesión
        session([
            'selected_team_id' => $request->team_id,
            'selected_league_id' => $request->league_id,
            'selected_season_id' => $currentSeason->id
        ]);
        
        // Guardar la relación en la tabla user_teams
        $userId = auth()->id();
        
        // Verificar si ya existe una relación para este usuario y temporada
        $existingTeam = DB::table('user_teams')
            ->where('user_id', $userId)
            ->where('season_id', $currentSeason->id)
            ->first();
            
        if ($existingTeam) {
            // Actualizar el equipo del usuario para esta temporada
            DB::table('user_teams')
                ->where('user_id', $userId)
                ->where('season_id', $currentSeason->id)
                ->update([
                    'team_id' => $request->team_id,
                    'updated_at' => now()
                ]);
        } else {
            // Crear una nueva relación
            DB::table('user_teams')->insert([
                'user_id' => $userId,
                'team_id' => $request->team_id,
                'season_id' => $currentSeason->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Obtener el nombre del equipo y la competición para mostrarlo en el mensaje
        $team = DB::table('teams')
            ->where('id', $request->team_id)
            ->first();
            
        $league = DB::table('leagues')
            ->where('id', $request->league_id)
            ->first();
            
        return redirect()->route('dashboard')
            ->with('success', "¡Bienvenido al equipo {$team->name} en la competición {$league->name}!");
    }
}
