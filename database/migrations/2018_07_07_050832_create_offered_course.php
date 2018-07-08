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
<<<<<<< HEAD
                      
                      $table->foreign('classroom_id')
                      ->references('classroom_id')
                      ->on('Academic_Year');
                      
                      $table->foreign(['curriculum_year','course_id'])
                      ->references(['curriculum_year','course_id'])
                      ->on('Curriculums');
                       
                        $table->unique(['classroom_id','curriculum_year','course_id']);
                      
=======

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


>>>>>>> 9f61ae220877e00c2f6b824dd710572c9d62a85d
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
