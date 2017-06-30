<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('album_id')->unsigned()->index();
            $table->string('name');
            $table->string('description');

            $table->string('file_name');
            $table->string('type');
            $table->string('orientation')->nullable();
            $table->integer('size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('ratio', 10)->nullable();
            $table->integer('order');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('media');
    }
}
