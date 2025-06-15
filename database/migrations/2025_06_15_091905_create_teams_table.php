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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('short_name', 10)->nullable();
            $table->string('nickname', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100);
            $table->string('stadium_name', 100)->nullable();
            $table->integer('stadium_capacity')->unsigned()->nullable();
            $table->string('primary_color', 7)->default('#000000');
            $table->string('secondary_color', 7)->nullable();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('country');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
