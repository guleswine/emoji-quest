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
        Schema::create('enemies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('emoji');
            $table->integer('health')->default(100);
            $table->integer('attack');
            $table->integer('armor');
            $table->integer('attack_range')->default(1);
            $table->integer('experience');
            $table->integer('dodge')->default(0);
            $table->integer('critical_hit')->default(0);
            $table->integer('action_points');
            $table->integer('drop_item_id');
            $table->jsonb('attack_areas');
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
        Schema::dropIfExists('enemies');
    }
};
