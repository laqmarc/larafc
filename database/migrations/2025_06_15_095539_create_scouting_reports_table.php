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
        Schema::create('scouting_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scout_id');
            $table->unsignedBigInteger('player_id');
            $table->date('report_date');
            $table->unsignedTinyInteger('technical_rating')->nullable();  // 0-100
            $table->unsignedTinyInteger('mental_rating')->nullable();     // 0-100
            $table->unsignedTinyInteger('physical_rating')->nullable();   // 0-100
            $table->unsignedTinyInteger('potential_rating')->nullable();  // 0-100 (potencial futuro)
            $table->text('notes')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('scout_id')
                  ->references('id')
                  ->on('scouts');

            $table->foreign('player_id')
                  ->references('id')
                  ->on('players');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scouting_reports');
    }
};
