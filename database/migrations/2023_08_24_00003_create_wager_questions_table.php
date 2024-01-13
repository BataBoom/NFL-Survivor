<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagersQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wager_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id')->index();
            $table->integer('week')->nullable();
            $table->enum('league', ['nfl', 'mlb', 'nba', 'ncaaf', 'wrestling', 'mma', 'nhl']);
            $table->string('question');
            $table->dateTime('starts_at', $precision = 0);
            $table->boolean('status')->default(true);
            $table->unique(['question', 'game_id', 'week']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wagers_questions');
    }
}
