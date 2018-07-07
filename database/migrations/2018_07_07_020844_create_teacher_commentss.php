<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherCommentss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Teacher_Comments', function (Blueprint $table) {
           $table->string('student_id',50);
           $table->unsignedTinyInteger('quater');
           $table->unsignedTinyInteger('semester');
           $table->unsignedSmallInteger('academic_year');
           $table->dateTimeTz('datetime');

           $table->text('comment');

           $table->unsignedTinyInteger('data_status');

           $table->primary(['student_id','quater','semester','academic_year','datetime']);
           $table->foreign('data_status')
                      ->references('data_status')
                      ->on('Data_Status');
                      });
        $table->foreign('student_id')
        ->references('student_id')
        ->on('Students');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Teacher_Comments', function (Blueprint $table) {
            Schema::dropIfExists('Data_Status');
        });
    }
}
