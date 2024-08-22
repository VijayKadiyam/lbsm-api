<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserPrograms2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_programs', function (Blueprint $table) {
            $table->boolean('status')->default(true)->comment('0 = PENDING; 1 = ONGOING; 2 = COMPLETED');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_programs', function (Blueprint $table) {
            //
        });
    }
}
