<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserProgramTasks2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_program_tasks', function (Blueprint $table) {
            $table->string('imagepath1')->nullable();
            $table->string('imagepath2')->nullable();
            $table->string('imagepath3')->nullable();
            $table->string('imagepath4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_program_tasks', function (Blueprint $table) {
            //
        });
    }
}
