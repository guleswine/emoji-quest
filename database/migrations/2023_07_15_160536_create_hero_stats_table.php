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
        Schema::create('hero_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('hero_id');
            $table->string('attribute');
            $table->integer('value');
            $table->integer('final_value');
            $table->integer('flat_buff')->nullable();
            $table->integer('percent_buff')->nullable();
            $table->integer('flat_improvment')->nullable();
            $table->integer('percent_improvment')->nullable();
            $table->integer('base_value');
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
        Schema::dropIfExists('hero_stats');
    }
};
