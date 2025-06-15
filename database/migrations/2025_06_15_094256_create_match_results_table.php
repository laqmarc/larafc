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
        Schema::create('match_results', function (Blueprint $table) {
            $table->unsignedBigInteger('match_id')->primary();
            $table->unsignedTinyInteger('home_score')->default(0);
            $table->unsignedTinyInteger('away_score')->default(0);
            $table->unsignedBigInteger('winner_team_id')->nullable();
            $table->boolean('extra_time_played')->default(false);
            $table->boolean('penalties_played')->default(false);
            $table->unsignedTinyInteger('home_penalties')->nullable();
            $table->unsignedTinyInteger('away_penalties')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('match_id')
                  ->references('id')
                  ->on('matches')
                  ->onDelete('cascade');

            $table->foreign('winner_team_id')
                  ->references('id')
                  ->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_results');
    }
};
