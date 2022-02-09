<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKarcoTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karco_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->nullable();
            $table->integer('ship_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('department', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('signed_on', 100)->nullable();
            $table->string('video_title', 100)->nullable();
            $table->string('no_of_preview_watched', 100)->nullable();
            $table->string('no_of_video_watched', 100)->nullable();
            $table->string('obtained_marks', 100)->nullable();
            $table->string('total_marks', 100)->nullable();
            $table->string('percentage', 100)->nullable();
            $table->string('done_on', 100)->nullable();
            $table->string('due_days', 100)->nullable();
            $table->string('assessment_status', 100)->nullable();
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
        Schema::dropIfExists('karco_tasks');
    }
}
