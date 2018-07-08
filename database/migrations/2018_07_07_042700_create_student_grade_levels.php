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
<<<<<<< HEAD
                      
                      $table->primary(['student_id','classroom_id']);
                      
=======

                      $table->primary(['student_id','classroom_id']);

>>>>>>> 9f61ae220877e00c2f6b824dd710572c9d62a85d
                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');
                      $table->foreign('classroom_id')
                      ->references('classroom_id')
<<<<<<< HEAD
                      ->on('Academic_Year');
            
=======
                      ->on('Academin_Year');

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
        Schema::table('Student_Grade_Levels', function (Blueprint $table) {
            //
        });
    }
}
