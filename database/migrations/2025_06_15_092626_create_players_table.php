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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('position', 10)->default('MF');
            $table->string('jersey_number', 3)->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('nationality', 50)->default('España');
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->unsignedSmallInteger('weight_kg')->nullable();
            $table->enum('preferred_foot', ['left', 'right', 'both'])->default('right');
            $table->string('photo_url', 255)->nullable();
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->decimal('value', 15, 2)->default(1000000);
            $table->decimal('wage', 15, 2)->default(10000);
            $table->date('contract_until')->nullable();
            $table->boolean('is_injured')->default(false);
            $table->text('injury_details')->nullable();
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
