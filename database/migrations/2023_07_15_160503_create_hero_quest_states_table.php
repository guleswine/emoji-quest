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
        Schema::create('hero_quest_states', function (Blueprint $table) {
            $table->id();
            $table->integer('hero_id');
            $table->integer('quest_id');
            $table->boolean('completed')->default(false);
            $table->integer('quest_state_id');
            $table->integer('current_progress')->default(0);
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
        Schema::dropIfExists('hero_quest_states');
    }
};
