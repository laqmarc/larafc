<?php

namespace Database\Seeders\Teams;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpanishTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            // LaLiga EA Sports
            [
                'name' => 'Real Madrid',
                'short_name' => 'RMA',
                'nickname' => 'Los Blancos',
                'city' => 'Madrid',
                'country' => 'España',
                'stadium_name' => 'Santiago Bernabéu',
                'stadium_capacity' => 81044,
                'primary_color' => '#00529F',
                'secondary_color' => '#FEBE10',
                'logo_path' => null,
                'description' => 'El Real Madrid Club de Fútbol es una entidad polideportiva con sede en Madrid, España.'
            ],
            [
                'name' => 'FC Barcelona',
                'short_name' => 'FCB',
                'nickname' => 'Blaugrana',
                'city' => 'Barcelona',
                'country' => 'España',
                'stadium_name' => 'Spotify Camp Nou',
                'stadium_capacity' => 99354,
                'primary_color' => '#A50044',
                'secondary_color' => '#004D98',
                'logo_path' => null,
                'description' => 'Futbol Club Barcelona, conocido popularmente como Barça, es una entidad polideportiva con sede en Barcelona, España.'
            ],
            
            // Podemos añadir más equipos de otras divisiones si es necesario
        ];

        foreach ($teams as $team) {
            DB::table('teams')->updateOrInsert(
                ['name' => $team['name']],
                $team
            );
        }
    }
}
