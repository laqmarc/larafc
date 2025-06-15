<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('code', 4)->unique();
            $table->string('name', 50);
            $table->timestamps();
        });

        // Insertar datos iniciales
        DB::table('positions')->insert([
            ['code' => 'GK',  'name' => 'Goalkeeper', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SW',  'name' => 'Sweeper', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'RB',  'name' => 'Right back', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'RWB', 'name' => 'Right wing-back', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CB',  'name' => 'Center back', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LWB', 'name' => 'Left wing-back', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LB',  'name' => 'Left back', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CDM', 'name' => 'Defensive midfielder', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CM',  'name' => 'Central midfielder', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAM', 'name' => 'Attacking midfielder', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LM',  'name' => 'Left midfielder', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'RM',  'name' => 'Right midfielder', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LW',  'name' => 'Left winger', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'RW',  'name' => 'Right winger', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CF',  'name' => 'Centre forward', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ST',  'name' => 'Striker', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SS',  'name' => 'Second striker', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'WF',  'name' => 'Withdrawn forward', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
