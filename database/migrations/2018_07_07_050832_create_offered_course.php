<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferedCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Offered_Courses', function (Blueprint $table) {
                      $table->unsignedInteger('classroom_id');
                      $table->unsignedSmallInteger('curriculum_year');
                      $table->string('course_id',20);
                      $table->unsignedInteger('open_course_id')->unique()->autoIncrement();
                      $table->boolean('is_elective');
                      $table->float('credits');

                      $table->foreign('classroom_id')
                      ->references('classroom_id')
                      ->on('Academin_Year');
                      $table->foreign('curriculum_year')
                      ->references('curriculum_year')
                      ->on('Academin_Year');

                      $table->foreign('curriculum_year')
                      ->references('curriculum_year')
                      ->on('Curriculums');
                      $table->foreign('course_id')
                      ->references('course_id')
                      ->on('Curriculums');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Offered_Courses', function (Blueprint $table) {
            Schema::dropIfExists('Offered_Courses');
        });
    }
}
