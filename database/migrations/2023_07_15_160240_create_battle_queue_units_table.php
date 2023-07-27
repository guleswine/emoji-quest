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
        Schema::create('battle_queue_units', function (Blueprint $table) {
            $table->id();
            $table->integer('battle_id');
            $table->string('type')->nullable();
            $table->integer('object_id');
            $table->integer('order');
            $table->string('object_name');
            $table->integer('health');
            $table->integer('action_points');
            $table->string('emoji_name');
            $table->string('name');
            $table->string('protected_area')->nullable();
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
        Schema::dropIfExists('battle_queue_units');
    }
};
