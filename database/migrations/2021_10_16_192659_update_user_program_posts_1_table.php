<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserProgramPosts1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_program_posts', function (Blueprint $table) {
            $table->renameColumn('promote_date','promotion_date')->nullable();
            $table->string('remarks',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_program_posts', function (Blueprint $table) {
            //
        });
    }
}
