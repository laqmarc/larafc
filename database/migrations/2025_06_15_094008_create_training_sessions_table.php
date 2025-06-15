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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->date('session_date');
            $table->enum('session_type', ['general', 'fitness', 'tactics', 'set_pieces', 'goalkeeper', 'individual']);
            $table->enum('intensity', ['low', 'medium', 'high'])->default('medium');
            $table->unsignedSmallInteger('duration_minutes')->default(90);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('training_sessions');
    }
};
