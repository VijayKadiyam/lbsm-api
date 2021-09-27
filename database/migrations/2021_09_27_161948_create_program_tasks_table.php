<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('program_id')->nullable();
            $table->integer('program_post_id')->nullable();
            $table->integer('serial_no')->nullable();
            $table->string('task')->nullable();
            $table->string('objective')->nullable();
            $table->string('material')->nullable();
            $table->string('process')->nullable();
            $table->integer('no_of_contracts')->default(0);
            $table->integer('time_required')->default(0);
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
        Schema::dropIfExists('program_tasks');
    }
}
