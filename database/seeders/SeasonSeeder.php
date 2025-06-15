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
            
            // Obtener o crear una liga por defecto (LaLiga España)
            $leagueId = $this->getOrCreateDefaultLeague($now);
            
            // Crear la temporada actual
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            
            DB::table('seasons')->insert([
                'league_id' => $leagueId,
                'name' => "Temporada $currentYear/$nextYear",
                'start_date' => "$currentYear-08-15",
                'end_date' => "$nextYear-05-31",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            // Crear la próxima temporada
            DB::table('seasons')->insert([
                'league_id' => $leagueId,
                'name' => "Temporada $nextYear/" . ($nextYear + 1),
                'start_date' => "$nextYear-08-15",
                'end_date' => "$nextYear-05-31",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            $this->command->info('Temporadas creadas exitosamente.');
        } else {
            $this->command->info('Ya existen temporadas en la base de datos.');
        }
    }
    
    /**
     * Obtiene o crea una liga por defecto (LaLiga España)
     */
    private function getOrCreateDefaultLeague($now)
    {
        $league = DB::table('leagues')
            ->where('name', 'LaLiga')
            ->orWhere('name', 'La Liga')
            ->orWhere('name', 'LaLiga Santander')
            ->orWhere('name', 'LaLiga EA Sports')
            ->first();
            
        if ($league) {
            return $league->id;
        }
        
        // Si no existe, crear la liga
        return DB::table('leagues')->insertGetId([
            'name' => 'LaLiga EA Sports',
            'country' => 'España',
            'level' => 1,
            'teams_number' => 20,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
