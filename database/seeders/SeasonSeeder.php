<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe una temporada
        if (DB::table('seasons')->count() === 0) {
            $now = now();
            
            // Crear la temporada actual
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            
            DB::table('seasons')->insert([
                'name' => "Temporada $currentYear/$nextYear",
                'start_date' => "$currentYear-08-15",
                'end_date' => "$nextYear-05-31",
                'is_current' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            // Crear la próxima temporada
            DB::table('seasons')->insert([
                'name' => "Temporada $nextYear/" . ($nextYear + 1),
                'start_date' => "$nextYear-08-15",
                'end_date' => "$nextYear-05-31",
                'is_current' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            $this->command->info('Temporadas creadas exitosamente.');
        } else {
            $this->command->info('Ya existen temporadas en la base de datos.');
        }
    }
    
    // Eliminada la función getOrCreateDefaultLeague ya que ya no es necesaria
}
