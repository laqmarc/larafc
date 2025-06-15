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
        Schema::create('team_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->primary();
            $table->enum('play_style', ['attacking', 'defensive', 'balanced'])->default('balanced');
            $table->unsignedTinyInteger('aggression_level')->default(50);  // 0 a 100
            $table->unsignedTinyInteger('pressing_level')->default(50);     // 0 a 100
            $table->enum('mentality', ['cautious', 'standard', 'positive'])->default('standard');
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
        Schema::dropIfExists('team_profiles');
    }
};
