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
        Schema::create('stadium_expansions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stadium_id');
            $table->enum('expansion_type', ['capacity', 'facility', 'luxury', 'roof', 'pitch_upgrade']);
            $table->string('description', 255)->nullable();
            $table->decimal('cost', 12, 2);
            $table->integer('capacity_increase')->default(0);
            $table->decimal('area_increase', 10, 2)->default(0);
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            // Clave foránea
            $table->foreign('stadium_id')
                  ->references('id')
                  ->on('stadiums')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stadium_expansions');
    }
};
