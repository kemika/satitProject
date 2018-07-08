<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Curriculums', function (Blueprint $table) {
            $table->unsignedSmallInteger('curriculum_year');
            $table->string('course_id',20);
            $table->string('course_name',100);
            $table->unsignedTinyInteger('min_grade_level');
            $table->unsignedTinyInteger('max_grade_level');
            $table->boolean('is_activity');
            $table->primary(['curriculum_year','course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Curriculums');
    }
}
