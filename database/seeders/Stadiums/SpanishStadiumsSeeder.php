<?php

namespace Database\Seeders\Stadiums;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpanishStadiumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stadiums = [
            // Estadio Santiago Bernabéu (Real Madrid)
            [
                'team_id' => $this->getTeamId('Real Madrid'),
                'name' => 'Estadio Santiago Bernabéu',
                'capacity' => 81044,
                'level' => 5, // Máximo nivel
                'pitch_type' => 'hybrid',
                'construction_cost' => 800000000.00,
                'maintenance_cost' => 2000000.00,
                'location' => 'Madrid, España',
                'address' => 'Av. de Concha Espina, 1, 28036 Madrid',
                'built_year' => 1947,
                'total_land_area' => 65000.00
            ],
            // Estadio Spotify Camp Nou (FC Barcelona)
            [
                'team_id' => $this->getTeamId('FC Barcelona'),
                'name' => 'Estadio Spotify Camp Nou',
                'capacity' => 99354,
                'level' => 5, // Máximo nivel
                'pitch_type' => 'hybrid',
                'construction_cost' => 1200000000.00, // Incluyendo renovaciones
                'maintenance_cost' => 2500000.00,
                'location' => 'Barcelona, España',
                'address' => 'C. d\'Arístides Maillol, 12, 08028 Barcelona',
                'built_year' => 1957,
                'total_land_area' => 55000.00
            ]
        ];

        foreach ($stadiums as $stadium) {
            DB::table('stadiums')->updateOrInsert(
                ['team_id' => $stadium['team_id']],
                $stadium
            );
        }
    }

    /**
     * Obtiene el ID de un equipo por su nombre
     */
    private function getTeamId(string $teamName): int
    {
        $team = DB::table('teams')->where('name', $teamName)->first();
        
        if (!$team) {
            throw new \RuntimeException("No se encontró el equipo: {$teamName}");
        }
        
        return $team->id;
    }
}
