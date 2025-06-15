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
        Schema::create('team_budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->primary();
            $table->unsignedBigInteger('season_id');
            $table->decimal('balance', 14, 2)->default(0);
            $table->decimal('wage_budget', 14, 2)->default(0);
            $table->decimal('transfer_budget', 14, 2)->default(0);
            $table->timestamps();

            // Claves foráneas
            $table->foreign('team_id')
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
        Schema::dropIfExists('team_budgets');
    }
};
