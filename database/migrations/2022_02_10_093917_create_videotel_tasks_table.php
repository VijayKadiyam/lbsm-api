<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideotelTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videotel_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->nullable();
            $table->integer('ship_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('training_title', 100)->nullable();
            $table->string('module', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('date', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('score', 100)->nullable();
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
        Schema::dropIfExists('videotel_tasks');
    }
}
