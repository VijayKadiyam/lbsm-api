<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProgramTasks2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program_tasks', function (Blueprint $table) {
            $table->string('serial_no')->nullable()->change();
            $table->longText('task')->nullable()->change();
            $table->longText('objective')->nullable()->change();
            $table->longText('material')->nullable()->change();
            $table->longText('process')->nullable()->change();
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
