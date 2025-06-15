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
            $table->boolean('is_current')->default(false)->after('end_date');
        });
        
        // Establecer la temporada más reciente como actual
        DB::table('seasons')
            ->where('end_date', DB::table('seasons')->max('end_date'))
            ->update(['is_current' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('is_current');
        });
    }
};
