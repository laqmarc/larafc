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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('season_id');
            $table->dateTime('match_date');
            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');
            $table->unsignedBigInteger('stadium_id');
            $table->enum('status', ['scheduled', 'in_progress', 'completed'])->default('scheduled');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons');

            $table->foreign('home_team_id')
                  ->references('id')
                  ->on('teams');


            $table->foreign('away_team_id')
                  ->references('id')
                  ->on('teams');

            $table->foreign('stadium_id')
                  ->references('id')
                  ->on('stadiums');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
