<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StadiumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            Stadiums\SpanishStadiumsSeeder::class,
            // Otros seeders de estadios se pueden agregar aquí
            // Stadiums\EnglishStadiumsSeeder::class,
            // Stadiums\ItalianStadiumsSeeder::class,
        ]);
    }
}
