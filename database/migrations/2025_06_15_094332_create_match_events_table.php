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
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('player_id')->nullable();
            $table->enum('event_type', ['goal', 'assist', 'yellow_card', 'red_card', 'substitution']);
            $table->unsignedTinyInteger('event_minute');
            $table->unsignedBigInteger('related_player_id')->nullable();
            $table->string('event_extra_info', 255)->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('match_id')
                  ->references('id')
                  ->on('matches')
                  ->onDelete('cascade');

            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams');

            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');

            $table->foreign('related_player_id')
                  ->references('id')
                  ->on('players');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_events');
    }
};
