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
        Schema::create('youth_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('potential_rating')->nullable();
            $table->unsignedTinyInteger('current_rating')->nullable();
            $table->string('position_code', 4)->nullable();
            $table->boolean('promoted_to_senior')->default(false);
            $table->timestamps();

            // Clave foránea
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youth_players');
    }
};
