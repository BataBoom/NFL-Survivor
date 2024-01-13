<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survivor_registrations', function (Blueprint $table) {
            $table->id();
            $table->boolean('alive')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->references('id')->on('users')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('pool_id')->references('id')->on('pools')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unique(['pool_id', 'user_id']);
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survivor_registrations');
    }
};
