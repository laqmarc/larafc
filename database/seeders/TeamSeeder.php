<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            Teams\SpanishTeamsSeeder::class,
            // Otros seeders de equipos se pueden agregar aquí
            // Teams\EnglishTeamsSeeder::class,
            // Teams\ItalianTeamsSeeder::class,
        ]);
    }
}
