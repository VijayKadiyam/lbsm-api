<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProgramTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_program_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('user_id')->nullable();
            $table->integer('program_id')->nullable();
            $table->integer('program_task_id')->nullable();
            $table->integer('marks_obtained')->default(0);
            $table->integer('is_completed')->default(0);
            $table->string('completion_date')->nullable();
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
        Schema::dropIfExists('user_program_tasks');
    }
}
