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
        Schema::create('player_medical_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->date('report_date');
            $table->text('condition_summary')->nullable();
            $table->unsignedTinyInteger('fitness_level')->default(100);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Clave foránea
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
        Schema::dropIfExists('player_medical_reports');
    }
};
