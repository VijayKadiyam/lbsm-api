<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProgramTasks3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program_tasks', function (Blueprint $table) {
            $table->string('serial_no', 100)->nullable()->change();
            $table->string('no_of_contracts', 100)->nullable()->change();
            $table->string('time_required', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program_tasks', function (Blueprint $table) {
            //
        });
    }
}
