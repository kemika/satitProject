<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD
        Schema::create('Academic_Year', function (Blueprint $table) {
=======
        Schema::create('Academin_Year', function (Blueprint $table) {
>>>>>>> 9f61ae220877e00c2f6b824dd710572c9d62a85d
                      $table->unsignedSmallInteger('academic_year');
                      $table->unsignedTinyInteger('grade_level');
                      $table->unsignedTinyInteger('room');
                      $table->unsignedSmallInteger('curriculum_year');
<<<<<<< HEAD
                      
                      $table->unsignedInteger('classroom_id')->autoIncrement();
                      $table->unsignedSmallInteger('total_days');
                      
                      $table->unique(['academic_year','grade_level','room','curriculum_year'], 'academic_year_unique');
=======

                      $table->unsignedInteger('classroom_id')->unique()->autoIncrement();
                      $table->unsignedSmallInteger('total_days');

                      $table->primary(['academic_year','grade_level','room','curriculum_year']);
>>>>>>> 9f61ae220877e00c2f6b824dd710572c9d62a85d
                      $table->foreign('curriculum_year')
                      ->references('curriculum_year')
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
<<<<<<< HEAD
        Schema::table('Academic_Year', function (Blueprint $table) {
=======
        Schema::table('Academin_Year', function (Blueprint $table) {
>>>>>>> 9f61ae220877e00c2f6b824dd710572c9d62a85d
            //
        });
    }
}
