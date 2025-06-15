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
        Schema::create('team_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('season_id');
            $table->smallInteger('shirt_number')->nullable();
            $table->date('signed_at')->nullable();
            $table->date('released_at')->nullable();
            $table->enum('contract_type', ['permanent', 'loan', 'youth'])->default('permanent');
            $table->decimal('salary', 12, 2)->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams');

            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');

            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons');

            // Clave única compuesta
            $table->unique(['team_id', 'player_id', 'season_id'], 'uq_tp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_players');
    }
};
