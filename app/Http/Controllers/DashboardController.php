<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del equipo
     */
    public function index()
    {
        $user = Auth::user();
        
        // Agregar logs de depuración
        \Log::info('=== DASHBOARD - INICIO ===');
        \Log::info('Usuario ID: ' . $user->id);
        \Log::info('Sesión:', [
            'selected_team_id' => session('selected_team_id'),
            'selected_league_id' => session('selected_league_id'),
            'selected_season_id' => session('selected_season_id')
        ]);
        \Log::info('==========================');
        
        // Obtener información del equipo seleccionado
        $team = DB::table('teams')
            ->where('id', session('selected_team_id'))
            ->first();
            
        if (!$team) {
            \Log::warning('Usuario sin equipo seleccionado, redirigiendo a selección de equipo');
            return redirect()->route('select.team.form')
                ->with('error', 'No tienes ningún equipo seleccionado.');
        }
        
        // Obtener información financiera básica (esto es un ejemplo, ajusta según tu esquema de base de datos)
        $financials = [
            'balance' => 5000000, // Ejemplo: 5 millones de euros
            'weekly_wages' => 250000, // Ejemplo: 250k euros/semana
            'transfer_budget' => 10000000, // Ejemplo: 10 millones de presupuesto
            'projected_income' => [
                'matchday' => 500000,
                'sponsors' => 750000,
                'merchandising' => 250000
            ],
            'projected_expenses' => [
                'wages' => 1000000,
                'facilities' => 150000,
                'other' => 50000
            ]
        ];
        
        // Próximos partidos (ejemplo)
        $upcomingMatches = [
            ['opponent' => 'Rival FC', 'competition' => 'Liga', 'date' => '2025-06-22', 'home_away' => 'H'],
            ['opponent' => 'Otro Equipo', 'competition' => 'Copa', 'date' => '2025-06-29', 'home_away' => 'A']
        ];
        
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
        
        // Jugadores clave - Usando la tabla intermedia team_players
        // Solo usamos columnas que sabemos que existen
        try {
            $keyPlayers = DB::table('players')
                ->join('team_players', 'players.id', '=', 'team_players.player_id')
                ->where('team_players.team_id', $team->id)
                ->where('team_players.season_id', $currentSeason->id)
                ->orderBy('players.last_name')  // Ordenamos por apellido
                ->select([
                    'players.id',
                    'players.first_name',
                    'players.last_name',
                    'players.nationality',
                    'team_players.shirt_number',
                    'team_players.contract_type'
                ])
                ->limit(5)
                ->get();
                
            // Si no hay jugadores, creamos una colección vacía para evitar errores en la vista
            if ($keyPlayers->isEmpty()) {
                $keyPlayers = collect();
                \Log::warning('No se encontraron jugadores para el equipo en esta temporada', [
                    'team_id' => $team->id,
                    'season_id' => $currentSeason->id
                ]);
            }
                
            \Log::info('Jugadores clave encontrados:', ['count' => $keyPlayers->count()]);
            \Log::debug('Datos de jugadores:', $keyPlayers->toArray());
                
        } catch (\Exception $e) {
            \Log::error('Error al obtener jugadores del equipo', [
                'error' => $e->getMessage(),
                'team_id' => $team->id,
                'season_id' => $currentSeason->id
            ]);
            $keyPlayers = collect();
        }
        
        return view('dashboard.index', compact('team', 'financials', 'upcomingMatches', 'keyPlayers'));
    }
    
    /**
     * Muestra la sección de finanzas
     */
    public function finances()
    {
        $team = DB::table('teams')
            ->where('id', session('selected_team_id'))
            ->first();
            
        // Datos financieros detallados
        $finances = [
            'balance' => 5000000,
            'revenue' => [
                'matchday' => 500000,
                'sponsors' => 750000,
                'merchandising' => 250000,
                'prizes' => 1000000,
                'transfers' => 2000000
            ],
            'expenses' => [
                'wages' => 1000000,
                'facilities' => 150000,
                'staff' => 300000,
                'transfers' => 500000,
                'youth' => 100000,
                'other' => 50000
            ],
            'transactions' => [
                ['date' => '2025-06-10', 'description' => 'Pago de patrocinadores', 'amount' => 250000, 'type' => 'ingreso'],
                ['date' => '2025-06-08', 'description' => 'Salarios jugadores', 'amount' => -1000000, 'type' => 'gasto'],
                ['date' => '2025-06-05', 'description' => 'Ingresos taquilla', 'amount' => 180000, 'type' => 'ingreso'],
                ['date' => '2025-06-01', 'description' => 'Mantenimiento instalaciones', 'amount' => -75000, 'type' => 'gasto']
            ]
        ];
        
        return view('dashboard.finances', compact('team', 'finances'));
    }
    
    /**
     * Muestra la plantilla del equipo
     */
    public function squad()
    {
        $user = Auth::user();
        $team = Team::with(['players' => function($query) {
            $query->orderByRaw("FIELD(position, 'GK', 'DF', 'MF', 'FW')")
                  ->orderBy('last_name');
        }])->findOrFail(session('selected_team_id'));
        
        // Agrupar jugadores por posición
        $groupedPlayers = [
            'GK' => $team->players->where('position', 'GK'),
            'DF' => $team->players->where('position', 'DF'),
            'MF' => $team->players->where('position', 'MF'),
            'FW' => $team->players->where('position', 'FW'),
        ];
        
        // Estadísticas rápidas
        $stats = [
            'total_players' => $team->players->count(),
            'average_age' => round($team->players->avg('age'), 1),
            'average_rating' => round($team->players->avg('rating'), 1),
            'total_value' => $team->players->sum('value'),
            'total_wage' => $team->players->sum('wage'),
            'injured_players' => $team->players->where('is_injured', true)->count(),
        ];
        
        // Jugadores por nacionalidad
        $nationalities = $team->players->groupBy('nationality')
            ->map->count()
            ->sortDesc()
            ->take(5);
        
        return view('dashboard.squad', compact('team', 'groupedPlayers', 'stats', 'nationalities'));
    }
}
