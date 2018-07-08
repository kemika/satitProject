<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Activity_Records', function (Blueprint $table) {
                      $table->string('student_id',50);
                      $table->unsignedInteger('open_course_id');
                      $table->unsignedTinyInteger('semester');
                      $table->unsignedSmallInteger('academic_year');
                      $table->dateTimeTz('datetime');
<<<<<<< HEAD
                      
                      $table->unsignedTinyInteger('grade_status');
                      $table->unsignedTinyInteger('data_status');
                      
                      $table->primary(['student_id','open_course_id','semester','datetime'], 'activity_records_primary');
                      
                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');
                      
                      $table->foreign('data_status')
                      ->references('data_status')
                      ->on('Data_Status');
                      
                      $table->foreign('open_course_id')
                      ->references('open_course_id')
                      ->on('Offered_Courses');
                      
=======

                      $table->unsignedTinyInteger('grade_status');
                      $table->unsignedTinyInteger('data_status');

                      $table->primary(['student_id','open_course_id','semester','datetime']);

                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');

                      $table->foreign('data_status')
                      ->references('data_status')
                      ->on('Data_Status');

                      $table->foreign('open_course_id')
                      ->references('open_course_id')
                      ->on('Offered_Courses');

>>>>>>> 9f61ae220877e00c2f6b824dd710572c9d62a85d
                      $table->foreign('grade_status')
                      ->references('grade_status')
                      ->on('Grade_Status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Activity_Records', function (Blueprint $table) {
            Schema::dropIfExists('Activity_Records');
        });
    }
}
