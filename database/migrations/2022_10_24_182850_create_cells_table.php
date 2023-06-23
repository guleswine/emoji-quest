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
        Schema::create('cells', function (Blueprint $table) {
            $table->comment('A parth of map');
            $table->id();
            $table->integer('map_id');
            $table->string('name');
            $table->string('emoji');
            $table->enum('surface_type', ['impassable', 'ground', 'water', 'snow', 'ice', 'swamp', 'sand', 'solid'])->default('ground');
            $table->integer('x');
            $table->integer('y');
            $table->integer('z')->default(0);
            $table->integer('size')->default(8);
            $table->integer('entrance_to_map_id')->nullable();
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
        Schema::dropIfExists('cells');
    }
};
