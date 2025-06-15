<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leagues = [
            // España
            ['name' => 'LaLiga EA Sports', 'country' => 'España', 'type' => 'league'],
            ['name' => 'LaLiga Hypermotion', 'country' => 'España', 'type' => 'league'],
            ['name' => 'LaLiga 3era', 'country' => 'España', 'type' => 'league'],
            ['name' => 'LaLiga 4arta', 'country' => 'España', 'type' => 'league'],
            ['name' => 'Copa del Rey', 'country' => 'España', 'type' => 'cup'],
            
            // Inglaterra
            ['name' => 'Premier League', 'country' => 'Inglaterra', 'type' => 'league'],
            ['name' => 'EFL Championship', 'country' => 'Inglaterra', 'type' => 'league'],
            ['name' => 'FA Cup', 'country' => 'Inglaterra', 'type' => 'cup'],
            ['name' => 'Carabao Cup', 'country' => 'Inglaterra', 'type' => 'cup'],
            
            // Italia
            ['name' => 'Serie A', 'country' => 'Italia', 'type' => 'league'],
            ['name' => 'Serie B', 'country' => 'Italia', 'type' => 'league'],
            ['name' => 'Coppa Italia', 'country' => 'Italia', 'type' => 'cup'],
            
            // Alemania
            ['name' => 'Bundesliga', 'country' => 'Alemania', 'type' => 'league'],
            ['name' => '2. Bundesliga', 'country' => 'Alemania', 'type' => 'league'],
            ['name' => 'DFB-Pokal', 'country' => 'Alemania', 'type' => 'cup'],
            
            // Francia
            ['name' => 'Ligue 1', 'country' => 'Francia', 'type' => 'league'],
            ['name' => 'Ligue 2', 'country' => 'Francia', 'type' => 'league'],
            ['name' => 'Coupe de France', 'country' => 'Francia', 'type' => 'cup'],
            
            // Portugal
            ['name' => 'Liga Portugal', 'country' => 'Portugal', 'type' => 'league'],
            ['name' => 'Taça de Portugal', 'country' => 'Portugal', 'type' => 'cup'],
            
            // Países Bajos
            ['name' => 'Eredivisie', 'country' => 'Países Bajos', 'type' => 'league'],
            ['name' => 'KNVB Cup', 'country' => 'Países Bajos', 'type' => 'cup'],
            
            // Bélgica
            ['name' => 'Jupiler Pro League', 'country' => 'Bélgica', 'type' => 'league'],
            
            // Escocia
            ['name' => 'Scottish Premiership', 'country' => 'Escocia', 'type' => 'league'],
            
            // Turquía
            ['name' => 'Süper Lig', 'country' => 'Turquía', 'type' => 'league'],
            
            // Competiciones internacionales
            ['name' => 'UEFA Champions League', 'country' => 'Europa', 'type' => 'championship'],
            ['name' => 'UEFA Europa League', 'country' => 'Europa', 'type' => 'championship'],
            ['name' => 'UEFA Europa Conference League', 'country' => 'Europa', 'type' => 'championship'],
            ['name' => 'UEFA Super Cup', 'country' => 'Europa', 'type' => 'championship'],
            
            // Amistosos
            ['name' => 'Amistosos Internacionales', 'country' => 'Internacional', 'type' => 'friendly'],
        ];

        // Insertar las ligas en la base de datos
        foreach ($leagues as $league) {
            DB::table('leagues')->updateOrInsert(
                ['name' => $league['name']],
                $league
            );
        }
    }
}
