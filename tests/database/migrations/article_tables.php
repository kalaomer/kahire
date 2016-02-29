<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ArticleTables extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->string('title');

            $table->integer('author_id')->unsigned();

            $table->foreign('author_id')->references('id')->on('authors');
        });

        Schema::create('article_tag', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('article_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('tags');
        Schema::drop('authors');
        Schema::drop('articles');
        Schema::drop('article_tag');
    }
}
