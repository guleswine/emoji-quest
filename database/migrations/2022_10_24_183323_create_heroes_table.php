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
        Schema::create('heroes', function (Blueprint $table) {
            $table->comment('Main users units on the map');
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->string('emoji');
            $table->integer('size')->default(10);
            $table->integer('map_id')->nullable();
            $table->integer('cell_id')->nullable();
            $table->integer('lvl')->default(1);
            $table->integer('experience')->default(0);
            $table->integer('experience_total');
            $table->integer('coins')->default(0);
            $table->integer('skill_points')->default(0);
            $table->string('state_name')->default('traveler');
            $table->integer('state_object_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heroes');
    }
};
