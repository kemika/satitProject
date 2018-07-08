<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGradeLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Student_Grade_Levels', function (Blueprint $table) {
                      $table->unsignedInteger('classroom_id');
                      $table->string('student_id',50);

                      $table->primary(['student_id','classroom_id']);
                       $table->timestamps();

                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');
                      $table->foreign('classroom_id')
                      ->references('classroom_id')
                      ->on('Academic_Year');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Student_Grade_Levels', function (Blueprint $table) {
            //
        });
    }
}
