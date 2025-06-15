<?php

namespace Database\Seeders\Players;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SpanishPlayersSeeder extends Seeder
{
    private $firstNames = ['A.', 'B.', 'C.', 'D.', 'E.', 'F.', 'G.', 'H.', 'I.', 'J.', 'K.', 'L.', 'M.', 'N.', 'O.', 'P.', 'Q.', 'R.', 'S.', 'T.', 'U.', 'V.', 'W.', 'X.', 'Y.', 'Z.'];
    private $lastNames = [
        'García', 'González', 'Rodríguez', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Gómez', 'Martín',
        'Jiménez', 'Ruiz', 'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Muñoz', 'Romero', 'Alonso', 'Gutiérrez',
        'Navarro', 'Torres', 'Domínguez', 'Vázquez', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez',
        'Molina', 'Morales', 'Ortega', 'Delgado', 'Castro', 'Ortiz', 'Rubio', 'Marín', 'Sanz', 'Iglesias',
        'Medina', 'Cortés', 'Castillo', 'Garrido', 'Lozano', 'Guerrero', 'Cano', 'Prieto', 'Méndez', 'Cruz',
        'Calvo', 'Gallego', 'Vidal', 'León', 'Herrera', 'Márquez', 'Peña', 'Flores', 'Cabrera', 'Campos',
        'Vega', 'Fuentes', 'Carrasco', 'Diez', 'Reyes', 'Caballero', 'Nieto', 'Aguilar', 'Pascual', 'Santana',
        'Herrero', 'Lorenzo', 'Montero', 'Hidalgo', 'Giménez', 'Ibáñez', 'Mora', 'Vicente', 'Santiago', 'Duran',
        'Benítez', 'Arias', 'Ferrer', 'Carmona', 'Crespo', 'Soto', 'Vargas', 'Román', 'Pastor', 'Sáez',
        'Soler', 'Velasco', 'Moya', 'Esteban', 'Parra', 'Bravo', 'Gallardo', 'Rojas', 'Pardo', 'Merino'
    ];
    
    private $nationalities = ['España', 'Argentina', 'Brasil', 'Francia', 'Portugal', 'Alemania', 'Holanda', 'Bélgica', 'Inglaterra', 'Italia'];
    
    // Grupos de posiciones por línea
    private $positionGroups = [
        'GK' => ['GK'],
        'DEF' => ['RB', 'RWB', 'CB', 'LWB', 'LB'],
        'MID' => ['CDM', 'CM', 'CAM', 'LM', 'RM'],
        'FWD' => ['LW', 'RW', 'CF', 'ST', 'SS', 'WF']
    ];
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los equipos españoles
        $teams = DB::table('teams')->where('country', 'España')->get();
        
        if ($teams->isEmpty()) {
            $this->command->info('No se encontraron equipos españoles. Creando jugadores para el primer equipo disponible...');
            $team = DB::table('teams')->first();
            if ($team) {
                $this->createPlayersForTeam($team->id, 25);
            } else {
                $this->command->error('No hay equipos disponibles en la base de datos.');
            }
            return;
        }
        
        $this->command->info('Encontrados ' . $teams->count() . ' equipos españoles.');
        
        foreach ($teams as $team) {
            $this->command->info("Creando 25 jugadores para el equipo: " . $team->name);
            $this->createPlayersForTeam($team->id, 25); // 25 jugadores por equipo
        }
    }
    
    private function createPlayersForTeam(int $teamId, int $count): void
    {
        $players = [];
        $now = now();
        $teamName = DB::table('teams')->where('id', $teamId)->value('name');
        
        $this->command->info("Creando $count jugadores para el equipo ID: $teamId ($teamName)");
        
        // Crear 2-3 porteros por equipo
        $goalkeepersCount = rand(2, 3);
        $this->createPlayersForPosition($teamId, 'GK', $goalkeepersCount, $now);
        
        // Crear 7-9 defensas
        $defendersCount = rand(7, 9);
        $this->createPlayersForPosition($teamId, 'DEF', $defendersCount, $now);
        
        // Crear 7-9 centrocampistas
        $midfieldersCount = rand(7, 9);
        $this->createPlayersForPosition($teamId, 'MID', $midfieldersCount, $now);
        
        // Crear 4-6 delanteros
        $forwardsCount = $count - $goalkeepersCount - $defendersCount - $midfieldersCount;
        $forwardsCount = max(4, min(6, $forwardsCount)); // Asegurar entre 4 y 6 delanteros
        $this->createPlayersForPosition($teamId, 'FWD', $forwardsCount, $now);
    }
    
    private function createPlayersForPosition(int $teamId, string $positionGroup, int $count, $now): void
    {
        $positionCodes = $this->positionGroups[$positionGroup];
        
        for ($i = 0; $i < $count; $i++) {
            // Seleccionar una posición principal al azar del grupo
            $mainPositionCode = $positionCodes[array_rand($positionCodes)];
            
            // Generar datos del jugador
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $nationality = $this->nationalities[array_rand($this->nationalities)];
            
            // Ajustar la edad según la posición (los porteros suelen ser mayores)
            $minAge = ($positionGroup === 'GK') ? 18 : 16;
            $maxAge = ($positionGroup === 'GK') ? 38 : 35;
            $age = rand($minAge, $maxAge);
            
            $dob = Carbon::now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 30));
            
            // Ajustar altura y peso según la posición
            $height = ($positionGroup === 'GK') ? rand(185, 200) : 
                     (($positionGroup === 'DEF') ? rand(180, 195) : 
                     (($positionGroup === 'MID') ? rand(170, 190) : rand(165, 185)));
                     
            $weight = ($positionGroup === 'GK') ? rand(75, 90) : 
                    (($positionGroup === 'DEF') ? rand(70, 85) : 
                    (($positionGroup === 'MID') ? rand(65, 80) : rand(60, 75)));
            
            $preferredFoot = (rand(1, 10) > 1) ? 'right' : 'left'; // 90% diestros, 10% zurdos
            
            // Insertar jugador
            $playerId = DB::table('players')->insertGetId([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'dob' => $dob->format('Y-m-d'),
                'nationality' => $nationality,
                'height_cm' => $height,
                'weight_kg' => $weight,
                'preferred_foot' => $preferredFoot,
                'photo_url' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            // Obtener el ID de la posición principal
            $mainPosition = DB::table('positions')->where('code', $mainPositionCode)->first();
            
            if ($mainPosition) {
                // Insertar posición principal
                DB::table('player_positions')->insert([
                    'player_id' => $playerId,
                    'position_id' => $mainPosition->id,
                    'is_primary' => true,
                    'preference_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                
                // Agregar 1-3 posiciones secundarias del mismo grupo
                $secondaryPositions = array_diff($positionCodes, [$mainPositionCode]);
                shuffle($secondaryPositions);
                $numSecondary = min(rand(1, 3), count($secondaryPositions));
                
                for ($j = 0; $j < $numSecondary; $j++) {
                    if (isset($secondaryPositions[$j])) {
                        $secPos = DB::table('positions')->where('code', $secondaryPositions[$j])->first();
                        if ($secPos) {
                            DB::table('player_positions')->insert([
                                'player_id' => $playerId,
                                'position_id' => $secPos->id,
                                'is_primary' => false,
                                'preference_order' => $j + 2,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                        }
                    }
                }
            }
            
            // Asociar jugador al equipo
            $this->assignPlayerToTeam($playerId, $teamId, $now);
        }
    }
    
    private function assignPlayerToTeam(int $playerId, int $teamId, $now): void
    {
        // Verificar si la tabla team_players existe
        if (!Schema::hasTable('team_players')) {
            return;
        }
        
        // Obtener la temporada actual (la más reciente que haya empezado)
        $season = DB::table('seasons')
            ->where('start_date', '<=', now())
            ->orderBy('start_date', 'desc')
            ->first();
            
        if (!$season) {
            // Si no hay temporada actual, usar la próxima que empieza
            $season = DB::table('seasons')
                ->where('start_date', '>', now())
                ->orderBy('start_date', 'asc')
                ->first();
                
            if (!$season) {
                // Si no hay ninguna temporada, crear una
                $leagueId = DB::table('leagues')
                    ->where('name', 'like', '%LaLiga%')
                    ->orWhere('name', 'like', '%Liga%')
                    ->value('id');
                    
                if (!$leagueId) {
                    $leagueId = DB::table('leagues')->value('id');
                }
                
                if ($leagueId) {
                    $currentYear = date('Y');
                    $nextYear = $currentYear + 1;
                    
                    $seasonId = DB::table('seasons')->insertGetId([
                        'league_id' => $leagueId,
                        'name' => "Temporada $currentYear/$nextYear",
                        'start_date' => "$currentYear-08-15",
                        'end_date' => "$nextYear-05-31",
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                } else {
                    $this->command->error('No se pudo crear una temporada porque no hay ligas disponibles.');
                    return;
                }
            } else {
                $seasonId = $season->id;
            }
        } else {
            $seasonId = $season->id;
        }
        
        // Obtener el número de dorsal disponible más bajo
        $usedNumbers = DB::table('team_players')
            ->where('team_id', $teamId)
            ->where('season_id', $seasonId)
            ->pluck('shirt_number')
            ->toArray();
            
        $shirtNumber = null;
        for ($i = 1; $i <= 99; $i++) {
            if (!in_array($i, $usedNumbers)) {
                $shirtNumber = $i;
                break;
            }
        }
        
        // Insertar en team_players
        DB::table('team_players')->insert([
            'player_id' => $playerId,
            'team_id' => $teamId,
            'season_id' => $seasonId,
            'shirt_number' => $shirtNumber,
            'signed_at' => now()->subDays(rand(30, 365)),
            'released_at' => null,
            'contract_type' => 'permanent',
            'salary' => rand(50000, 500000), // Salario aleatorio entre 50,000 y 500,000
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        $player = DB::table('players')->find($playerId);
        $this->command->info(sprintf(
            'Creado jugador: %s %s (%s) - Dorsal: %d - %s',
            $player->first_name,
            $player->last_name,
            $player->nationality,
            $shirtNumber,
            $this->getPlayerPositions($playerId)
        ));
    }
    
    private function getPlayerPositions(int $playerId): string
    {
        $positions = DB::table('player_positions')
            ->join('positions', 'player_positions.position_id', '=', 'positions.id')
            ->where('player_positions.player_id', $playerId)
            ->orderBy('player_positions.preference_order')
            ->pluck('positions.code')
            ->toArray();
            
        return implode(', ', $positions);
    }
}
