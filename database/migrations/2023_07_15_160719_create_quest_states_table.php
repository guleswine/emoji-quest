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
        Schema::create('quest_states', function (Blueprint $table) {
            $table->id();
            $table->integer('quest_id');
            $table->string('name');
            $table->integer('sort')->default(1);
            $table->string('event_class')->nullable();
            $table->integer('total_progress')->default(0);
            $table->jsonb('params')->nullable();
            $table->integer('object_id')->nullable();
            $table->boolean('final')->default(false);
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
        Schema::dropIfExists('quest_states');
    }
};
