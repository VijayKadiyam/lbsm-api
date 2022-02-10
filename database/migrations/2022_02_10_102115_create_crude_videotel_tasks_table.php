<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrudeVideotelTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crude_videotel_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_id', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('crew_id', 100)->nullable();
            $table->string('rank', 100)->nullable();
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
        Schema::dropIfExists('crude_videotel_tasks');
    }
}
