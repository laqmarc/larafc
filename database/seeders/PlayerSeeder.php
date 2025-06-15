<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que existan las posiciones
        $this->call([
            PositionSeeder::class,
        ]);
        
        // Asegurarse de que exista al menos una temporada
        $this->call([
            SeasonSeeder::class,
        ]);
        
        // Ejecutar los seeders de jugadores
        $this->call([
            Players\SpanishPlayersSeeder::class,
            // Otros seeders de jugadores se pueden agregar aquí
            // Players\EnglishPlayersSeeder::class,
            // Players\ItalianPlayersSeeder::class,
        ]);
    }
}
