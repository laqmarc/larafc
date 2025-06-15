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
        Schema::create('player_form', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('season_id');
            $table->unsignedBigInteger('match_id')->nullable();
            $table->unsignedTinyInteger('form_score')->default(50);
            $table->enum('morale', ['very_low', 'low', 'normal', 'high', 'very_high'])->default('normal');
            $table->unsignedTinyInteger('fatigue_level')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');

            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons');

            $table->foreign('match_id')
                  ->references('id')
                  ->on('matches')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_form');
    }
};
