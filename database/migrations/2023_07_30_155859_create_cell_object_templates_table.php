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
        Schema::create('cell_object_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index()->unique();
            $table->string('name');
            $table->string('emoji',50);
            $table->string('object_class')->nullable();
            $table->integer('object_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('size');
            $table->integer('priority');
            $table->boolean('use_as_background');
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
        Schema::dropIfExists('cell_object_templates');
    }
};
