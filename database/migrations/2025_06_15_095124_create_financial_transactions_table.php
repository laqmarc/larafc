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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('season_id');
            $table->decimal('amount', 12, 2);
            $table->string('description', 255)->nullable();
            $table->enum('transaction_type', ['income', 'expense']);
            $table->enum('category', [
                'ticketing', 
                'merchandising', 
                'salaries', 
                'transfers', 
                'sponsorship', 
                'misc'
            ]);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

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
        Schema::dropIfExists('financial_transactions');
    }
};
