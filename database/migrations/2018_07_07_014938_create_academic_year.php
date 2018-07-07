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
        Schema::create('Academin_Year', function (Blueprint $table) {
                      $table->unsignedSmallInteger('academic_year');
                      $table->unsignedTinyInteger('grade_level');
                      $table->unsignedTinyInteger('room');
                      $table->unsignedSmallInteger('curriculum_year');

                      $table->unsignedInteger('classroom_id')->unique()->autoIncrement();
                      $table->unsignedSmallInteger('total_days');

                      $table->primary(['academic_year','grade_level','room','curriculum_year']);
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
        Schema::table('Academin_Year', function (Blueprint $table) {
            //
        });
    }
}
