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
            $table->integer('cell_id');
            $table->integer('lvl')->default(1);
            $table->integer('experience')->default(0);
            $table->integer('health_base')->default(100);
            $table->integer('health_current')->default(100);
            $table->integer('attack_base')->default(5);
            $table->integer('attack_current')->default(5);
            $table->integer('armor_base')->default(1);
            $table->integer('armor_current')->default(1);
            $table->integer('action_points_base')->default(5);
            $table->integer('action_points_current')->default(5);
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
