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
        Schema::create('cell_objects', function (Blueprint $table) {
            $table->id();
            $table->integer('cell_id');
            $table->string('name');
            $table->string('emoji');
            $table->string('object_class');
            $table->integer('object_id');
            $table->string('type')->nullable();
            $table->integer('size')->default(8);
            $table->integer('priority');
            $table->boolean('use_as_background')->default(false);
            $table->integer('creator_hero_id')->nullable();

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
        Schema::dropIfExists('cell_objects');
    }
};
