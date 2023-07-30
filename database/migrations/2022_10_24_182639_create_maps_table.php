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
        Schema::create('maps', function (Blueprint $table) {
            $table->comment('Global game regions');
            $table->id();
            $table->string('key',32)->unique()->index();
            $table->string('name');
            $table->string('emoji');
            $table->integer('start_cell_id')->nullable();
            $table->integer('size_width');
            $table->integer('size_height');
            $table->string('ambient_color');
            $table->string('ambient_sound')->nullable();
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
        Schema::dropIfExists('maps');
    }
};
