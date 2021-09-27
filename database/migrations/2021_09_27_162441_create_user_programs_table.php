<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('user_id')->nullable();
            $table->integer('program_id')->nullable();
            $table->string('enrollment_date')->nullable();
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
        Schema::dropIfExists('user_programs');
    }
}
