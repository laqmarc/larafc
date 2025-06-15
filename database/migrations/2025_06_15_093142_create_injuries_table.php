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
        Schema::create('injuries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->string('injury_type', 100);
            $table->enum('severity', ['minor', 'moderate', 'serious']);
            $table->date('injured_on');
            $table->date('expected_return')->nullable();
            $table->date('actual_return')->nullable();
            $table->enum('cause', ['match', 'training', 'other'])->default('match');
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
        Schema::dropIfExists('injuries');
    }
};
