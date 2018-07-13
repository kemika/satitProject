<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeroomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeroom', function (Blueprint $table) {
          $table->string('teacher_id',100);
          $table->unsignedInteger('classroom_id');
          $table->date('date');
          $table->boolean('valid');

          $table->primary(['teacher_id','classroom_id']);

          $table->foreign('teacher_id')
          ->references('teacher_id')
          ->on('Teachers');

          $table->foreign('classroom_id')
          ->references('classroom_id')
          ->on('Academic_Year');

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
        Schema::dropIfExists('homeroom');
    }
}
