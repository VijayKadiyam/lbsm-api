<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRankLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rank_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_active')->default(true)->comment('used to define Active/Inactive');
            $table->boolean('is_deleted')->default(false)->comment('used for soft delete');
            $table->integer('site_id')->nullable()->comment('from Site table');
            $table->integer('user_id')->nullable()->comment('from User table');
            $table->integer('rank_id')->nullable()->comment('from Rank table');
            $table->boolean('status')->default(true)->comment('0 = PREVIOUS; 1 = CURRENT');
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
        Schema::dropIfExists('user_rank_logs');
    }
}
