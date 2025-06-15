<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Nombres de jugadores de ejemplo
     */
    protected $firstNames = [
        'Álvaro', 'David', 'Javier', 'Sergio', 'Carlos', 'Adrián', 'Rubén', 'Diego', 'Pablo', 'Raúl',
        'Jesús', 'Juan', 'José', 'Antonio', 'Manuel', 'Francisco', 'Luis', 'Miguel', 'Ángel', 'Pedro',
        'Daniel', 'Alejandro', 'Jorge', 'Fernando', 'Alberto', 'Jordi', 'Andrés', 'Marc', 'Santiago', 'Víctor'
    ];

    protected $lastNames = [
        'García', 'González', 'Rodríguez', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Gómez', 'Martín',
        'Jiménez', 'Ruiz', 'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Muñoz', 'Romero', 'Alonso', 'Gutiérrez',
        'Navarro', 'Torres', 'Domínguez', 'Vázquez', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez'
    ];

    protected $positions = ['GK', 'DF', 'MF', 'FW'];
    protected $nationalities = ['España', 'Argentina', 'Brasil', 'Francia', 'Alemania', 'Italia', 'Portugal', 'Países Bajos'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los equipos existentes
        $teams = Team::all();
        
        if ($teams->isEmpty()) {
            $this->command->info('No hay equipos en la base de datos. Por favor, ejecuta TeamSeeder primero.');
            return;
        }

        // Crear jugadores para cada equipo
        foreach ($teams as $team) {
            // Crear 2 porteros
            for ($i = 0; $i < 2; $i++) {
                $this->createPlayer($team->id, 'GK');
            }
            
            // Crear 8 defensas
            for ($i = 0; $i < 8; $i++) {
                $this->createPlayer($team->id, 'DF');
            }
            
            // Crear 8 centrocampistas
            for ($i = 0; $i < 8; $i++) {
                $this->createPlayer($team->id, 'MF');
            }
            
            // Crear 6 delanteros
            for ($i = 0; $i < 6; $i++) {
                $this->createPlayer($team->id, 'FW');
            }
        }
        
        $this->command->info('Jugadores creados exitosamente.');
    }
    
    /**
     * Crear un jugador con datos aleatorios
     */
    protected function createPlayer($teamId, $position = null)
    {
        $position = $position ?? $this->positions[array_rand($this->positions)];
        $age = rand(17, 38);
        $height = rand(165, 195);
        $weight = rand(60, 90);
        $rating = $this->calculateRating($position, $age);
        $value = $this->calculateValue($rating, $age);
        $wage = $this->calculateWage($value);
        
        return Player::create([
            'first_name' => $this->firstNames[array_rand($this->firstNames)],
            'last_name' => $this->lastNames[array_rand($this->lastNames)],
            'team_id' => $teamId,
            'position' => $position,
            'jersey_number' => (string)rand(1, 30),
            'dob' => now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 30)),
            'age' => $age,
            'nationality' => $this->nationalities[array_rand($this->nationalities)],
            'height_cm' => $height,
            'weight_kg' => $weight,
            'preferred_foot' => rand(0, 1) ? 'right' : 'left',
            'photo_url' => null,
            'rating' => $rating,
            'value' => $value,
            'wage' => $wage,
            'contract_until' => now()->addYears(rand(1, 5)),
            'is_injured' => rand(1, 100) <= 10, // 10% de probabilidad de estar lesionado
            'injury_details' => rand(1, 100) <= 10 ? 'Lesión menor' : null,
        ]);
    }
    
    /**
     * Calcular la valoración según la posición y edad
     */
    protected function calculateRating($position, $age)
    {
        $baseRating = rand(50, 80); // Valor base entre 50 y 80
        $ageFactor = $this->getAgeFactor($age);
        return round(min(99, $baseRating * $ageFactor), 1); // Máximo 99 de valoración
    }
    
    /**
     * Factor de edad para la valoración
     */
    protected function getAgeFactor($age)
    {
        if ($age < 20) return 0.8; // Jóvenes con menos valoración
        if ($age >= 20 && $age <= 28) return 1.0; // Máximo rendimiento
        if ($age > 28 && $age <= 32) return 0.95; // Ligera disminución
        return 0.85; // Disminución mayor después de los 32
    }
    
    /**
     * Calcular el valor económico del jugador
     */
    protected function calculateValue($rating, $age)
    {
        $baseValue = $rating * 100000; // Valor base según la valoración
        $ageFactor = $this->getAgeFactor($age);
        return round($baseValue * $ageFactor * rand(80, 120) / 100); // Añadir variación del 20%
    }
    
    /**
     * Calcular el salario semanal
     */
    protected function calculateWage($value)
    {
        // El salario semanal es aproximadamente el 0.5% del valor del jugador
        return round($value * 0.005);
    }
}
