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
        Schema::create('pools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['survivor', 'pickem', 'testing']);
            $table->double('cost', 8, 2)->default(0);
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->double('prize', 8, 3)->nullable();
            $table->enum('prize_type', ['crypto', 'credits', 'promotion'])->default('crypto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pools');
    }
};
