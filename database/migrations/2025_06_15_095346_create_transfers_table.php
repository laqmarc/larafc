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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('from_team_id')->nullable();
            $table->unsignedBigInteger('to_team_id')->nullable();
            $table->date('transfer_date');
            $table->decimal('transfer_fee', 12, 2)->nullable();
            $table->unsignedBigInteger('season_id');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');

            $table->foreign('from_team_id')
                  ->references('id')
                  ->on('teams');

            $table->foreign('to_team_id')
                  ->references('id')
                  ->on('teams');

            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
