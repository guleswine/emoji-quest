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
        Schema::create('hero_talents', function (Blueprint $table) {
            $table->id();
            $table->integer('hero_id');
            $table->integer('talent_id');
            $table->integer('level')->default(1);
            $table->integer('current_progress');
            $table->integer('total_progress');
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
        Schema::dropIfExists('hero_talents');
    }
};
