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
        Schema::create('player_match_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedTinyInteger('minutes_played')->default(0);
            $table->unsignedTinyInteger('goals')->default(0);
            $table->unsignedTinyInteger('assists')->default(0);
            $table->unsignedTinyInteger('yellow_cards')->default(0);
            $table->unsignedTinyInteger('red_cards')->default(0);
            $table->boolean('is_starter')->default(true);
            $table->boolean('is_substituted')->default(false);
            $table->timestamps();

            // Claves foráneas
            $table->foreign('match_id')
                  ->references('id')
                  ->on('matches');

            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');

            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams');
                  
            // Índice compuesto para evitar duplicados
            $table->unique(['match_id', 'player_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_match_stats');
    }
};
