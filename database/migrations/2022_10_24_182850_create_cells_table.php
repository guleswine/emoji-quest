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
            $table->string('emoji')->nullable();
            $table->string('surface_type')->default('ground');
            $table->integer('x');
            $table->integer('y');
            $table->integer('z')->default(0);
            $table->integer('size')->default(8);
            $table->integer('transfer_to_cell_id')->nullable();
            $table->timestamps();
            $table->unique(['map_id', 'x', 'y']);
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
