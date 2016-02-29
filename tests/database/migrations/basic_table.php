<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BasicTable extends Migration
{
    public function up()
    {
        Schema::create('basic', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id');
            $table->integer('integer');
            $table->string('string');
        });
    }

    public function down()
    {
        Schema::drop('basic');
    }
}
