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
        Schema::create('school_days', function (Blueprint $table) {
                      $table->unsignedSmallInteger('academic_year');
                      $table->unsignedTinyInteger('grade');
                      $table->unsignedTinyInteger('semester');
                      $table->unsignedSmallInteger('total_days');

                      $table->primary(['academic_year','grade_level','semester']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_days', function (Blueprint $table) {
            //
        });
    }
}
