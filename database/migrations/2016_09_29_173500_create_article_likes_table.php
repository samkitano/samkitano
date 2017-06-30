<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_likes', function (Blueprint $table) {
            $table->integer('user_id')
                  ->unsigned()
                  ->index();
            $table->integer('article_id')
                  ->unsigned()
                  ->index();

            $table->primary(['user_id', 'article_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_likes');
    }
}
