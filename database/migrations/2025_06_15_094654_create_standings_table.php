<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('season_id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedSmallInteger('played')->default(0);
            $table->unsignedSmallInteger('won')->default(0);
            $table->unsignedSmallInteger('draw')->default(0);
            $table->unsignedSmallInteger('lost')->default(0);
            $table->unsignedSmallInteger('goals_for')->default(0);
            $table->unsignedSmallInteger('goals_against')->default(0);
            $table->smallInteger('goal_diff')->default(0);
            $table->unsignedSmallInteger('points')->default(0);
            $table->unsignedSmallInteger('rank')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons');

            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams');
                  
            // Índice único para evitar duplicados
            $table->unique(['season_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standings', function (Blueprint $table) {
            // Eliminar las claves foráneas primero
            $table->dropForeign(['season_id']);
            $table->dropForeign(['team_id']);
        });
        
        // Luego eliminar la tabla
        Schema::dropIfExists('standings');
    }
};
