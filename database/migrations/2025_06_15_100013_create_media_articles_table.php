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
        Schema::create('media_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->dateTime('published_at')->useCurrent();
            $table->unsignedBigInteger('related_team_id')->nullable();
            $table->unsignedBigInteger('related_player_id')->nullable();
            $table->enum('type', ['news', 'rumor', 'interview', 'announcement'])->default('news');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('related_team_id')
                  ->references('id')
                  ->on('teams');

            $table->foreign('related_player_id')
                  ->references('id')
                  ->on('players');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_articles');
    }
};
