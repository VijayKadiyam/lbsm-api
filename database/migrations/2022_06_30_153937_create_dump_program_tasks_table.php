<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDumpProgramTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dump_program_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('user_id')->nullable();
            $table->integer('program_id')->nullable();
            $table->integer('user_program_id')->nullable();
            $table->integer('program_task_id')->nullable();
            $table->integer('marks_obtained')->default(0);
            $table->integer('is_completed')->default(0);
            $table->string('completion_date')->nullable();
            $table->string('imagepath1')->nullable();
            $table->string('imagepath2')->nullable();
            $table->string('imagepath3')->nullable();
            $table->string('imagepath4')->nullable();
            $table->integer('ship_id')->nullable();
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->integer('active')->default(true);
            $table->string('remark')->nullable();
            $table->string('subject')->nullable();
            $table->string('body')->nullable();
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
        Schema::dropIfExists('dump_program_tasks');
    }
}
