<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBehaviorRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Behavior_Records', function (Blueprint $table) {
                      $table->string('student_id',50);
                      $table->unsignedTinyInteger('behavior_type');
                      $table->unsignedTinyInteger('quater');
                      $table->unsignedTinyInteger('semester');
                      $table->unsignedSmallInteger('academic_year');
                      $table->dateTimeTz('datetime');

                      $table->float('grade');
                      $table->unsignedTinyInteger('data_status');

                      $table->primary(['student_id','behavior_type','quater','semester','academic_year','datetime']);
                      $table->foreign('student_id')
                      ->references('student_id')
                      ->on('Students');
                      $table->foreign('behavior_type')
                      ->references('behavior_type')
                      ->on('Behavior_Types');
                      $table->foreign('data_status')
                      ->references('data_status')
                      ->on('Data_Status');
                      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Behavior_Records', function (Blueprint $table) {
            //
        });
    }
}
