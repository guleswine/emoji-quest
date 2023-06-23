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
        Schema::create('hero_equipment', function (Blueprint $table) {
            $table->comment('Equiped items on hero');
            $table->id();
            $table->integer('hero_id');
            $table->string('name');
            $table->string('emoji')->nullable();
            $table->string('type');
            $table->string('category');
            $table->string('side');
            $table->integer('sort_order');
            $table->integer('item_id')->nullable();
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
        //
    }
};
