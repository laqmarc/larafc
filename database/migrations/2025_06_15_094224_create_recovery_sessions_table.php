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
        Schema::create('recovery_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->date('session_date');
            $table->enum('recovery_type', ['physiotherapy', 'massage', 'ice_bath', 'rest_day']);
            $table->unsignedTinyInteger('fatigue_recovered')->default(10);
            $table->unsignedTinyInteger('morale_boost')->default(5);
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
        Schema::dropIfExists('recovery_sessions');
    }
};
