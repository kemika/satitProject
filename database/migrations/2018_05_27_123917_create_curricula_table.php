<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curricula', function (Blueprint $table) {

            $table->increments('id');
            $table->string('year');
            $table->string('code')->default("Z000");
            $table->string('name')->default("Default value");
            $table->integer('min')->default(0);
            $table->integer('max')->default(0);
            $table->boolean('status')->default(false);
            $table->timestamps();
/*
            $table->increments('id');
            $table->string('year');
            $table->string('code');
            $table->string('name');
            $table->integer('min');
            $table->integer('max');
            $table->boolean('status')->default(false);
            $table->timestamps();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curricula');
    }
}
