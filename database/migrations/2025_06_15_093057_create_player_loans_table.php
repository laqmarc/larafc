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
        Schema::create('player_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_player_id');
            $table->unsignedBigInteger('to_team_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('team_player_id')
                  ->references('id')
                  ->on('team_players')
                  ->onDelete('cascade');

            $table->foreign('to_team_id')
                  ->references('id')
                  ->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_loans');
    }
};
