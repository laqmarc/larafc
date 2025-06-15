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
        Schema::table('seasons', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea primero
            $table->dropForeign(['league_id']);
            // Luego eliminar la columna
            $table->dropColumn('league_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            // Volver a agregar la columna league_id
            $table->foreignId('league_id')->after('id')->constrained('leagues')->onDelete('cascade');
        });
    }
};
