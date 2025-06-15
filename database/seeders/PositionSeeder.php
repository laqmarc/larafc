<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya hay posiciones
        if (DB::table('positions')->count() === 0) {
            $now = now();
            
            $positions = [
                ['code' => 'GK',  'name' => 'Portero', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'SW',  'name' => 'Líbero', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'RB',  'name' => 'Lateral derecho', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'RWB', 'name' => 'Carrilero derecho', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'CB',  'name' => 'Defensa central', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'LWB', 'name' => 'Carrilero izquierdo', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'LB',  'name' => 'Lateral izquierdo', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'CDM', 'name' => 'Mediocentro defensivo', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'CM',  'name' => 'Centrocampista', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'CAM', 'name' => 'Mediocentro ofensivo', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'LM',  'name' => 'Interior izquierdo', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'RM',  'name' => 'Interior derecho', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'LW',  'name' => 'Extremo izquierdo', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'RW',  'name' => 'Extremo derecho', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'CF',  'name' => 'Mediapunta', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'ST',  'name' => 'Delantero centro', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'SS',  'name' => 'Segundo delantero', 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'WF',  'name' => 'Delantero retrasado', 'created_at' => $now, 'updated_at' => $now],
            ];
            
            DB::table('positions')->insert($positions);
            $this->command->info(count($positions) . ' posiciones creadas exitosamente.');
        } else {
            $this->command->info('Ya existen posiciones en la base de datos.');
        }
    }
}
