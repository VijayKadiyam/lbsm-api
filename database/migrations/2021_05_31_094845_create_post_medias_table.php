<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->string('media_path', 100)->nullable();
            $table->string('media_caption', 100)->nullable();
            $table->integer('visibilty')->default(0);
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
        Schema::dropIfExists('post_medias');
    }
}
