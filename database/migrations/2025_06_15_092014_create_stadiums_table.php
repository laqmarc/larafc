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
        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->integer('capacity')->default(0);
            $table->integer('level')->default(1);
            $table->enum('pitch_type', ['grass', 'artificial', 'hybrid'])->default('grass');
            $table->decimal('construction_cost', 12, 2)->default(0);
            $table->decimal('maintenance_cost', 12, 2)->default(0);
            $table->string('location', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->year('built_year')->nullable();
            $table->decimal('total_land_area', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stadiums');
    }
};
