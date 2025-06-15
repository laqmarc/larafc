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
        Schema::create('player_trainings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_session_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedTinyInteger('performance_score')->default(50);
            $table->unsignedTinyInteger('fatigue_added')->default(10);
            $table->unsignedTinyInteger('injury_risk')->default(5);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('training_session_id')
                  ->references('id')
                  ->on('training_sessions')
                  ->onDelete('cascade');

            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');

            // Índice compuesto para evitar duplicados
            $table->unique(['training_session_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_trainings');
    }
};
