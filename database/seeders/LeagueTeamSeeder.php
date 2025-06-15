<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la temporada actual
        $season = DB::table('seasons')
            ->where('is_current', true)
            ->orWhere('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$season) {
            $this->command->error('No hay una temporada activa. Por favor, crea una temporada primero.');
            return;
        }

        // Obtener todas las ligas
        $leagues = DB::table('leagues')->get();
        
        // Obtener todos los equipos
        $teams = DB::table('teams')->get();

        if ($leagues->isEmpty() || $teams->isEmpty()) {
            $this->command->error('No hay ligas o equipos disponibles. Por favor, ejecuta los seeders de ligas y equipos primero.');
            return;
        }

        $this->command->info("Asociando equipos a ligas para la temporada: {$season->name}");

        // Para cada liga, asignar algunos equipos
        foreach ($leagues as $league) {
            // Tomar un número aleatorio de equipos para esta liga (entre 2 y 5)
            $teamsCount = rand(2, min(5, $teams->count()));
            $selectedTeams = $teams->random($teamsCount);

            foreach ($selectedTeams as $team) {
                // Verificar si ya existe esta asociación
                $exists = DB::table('league_team')
                    ->where('league_id', $league->id)
                    ->where('team_id', $team->id)
                    ->where('season_id', $season->id)
                    ->exists();

                if (!$exists) {
                    DB::table('league_team')->insert([
                        'league_id' => $league->id,
                        'team_id' => $team->id,
                        'season_id' => $season->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("  - Equipo '{$team->name}' añadido a la liga '{$league->name}'");
                }
            }
        }
    }
}
