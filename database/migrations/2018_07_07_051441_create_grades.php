<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Grades', function (Blueprint $table) {
                      $table->string('student_id',50);
                      $table->unsignedInteger('open_course_id');
                      $table->unsignedTinyInteger('quater');
                      $table->unsignedTinyInteger('semester');
                      $table->unsignedSmallInteger('academic_year');
                      $table->dateTimeTz('datetime');
<<<<<<< HEAD
                      
                      $table->float('grade');
                      $table->unsignedTinyInteger('grade_status');
                      $table->unsignedTinyInteger('data_status');
                      
                      $table->primary(['student_id','open_course_id','quater','semester','datetime'],'grades_primary');
                      
                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');
                      
                      $table->foreign('data_status')
                      ->references('data_status')
                      ->on('Data_Status');
                      
                      $table->foreign('open_course_id')
                      ->references('open_course_id')
                      ->on('Offered_Courses');
                      
                      $table->foreign('grade_status')
                      ->references('grade_status')
                      ->on('Grade_Status');
                      
                      
=======

                      $table->float('grade');
                      $table->unsignedTinyInteger('grade_status');
                      $table->unsignedTinyInteger('data_status');

                      $table->primary(['student_id','open_course_id','quater','semester','datetime']);

                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');

                      $table->foreign('data_status')
                      ->references('data_status')
                      ->on('Data_Status');

                      $table->foreign('open_course_id')
                      ->references('open_course_id')
                      ->on('Offered_Courses');

                      $table->foreign('grade_status')
                      ->references('grade_status')
                      ->on('Grade_Status');


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
        Schema::table('Grades', function (Blueprint $table) {
            Schema::dropIfExists('Grades');
        });
    }
}
