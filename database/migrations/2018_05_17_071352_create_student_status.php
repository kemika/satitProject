<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Student_Status', function (Blueprint $table) {
           $table->unsignedTinyInteger('student_status');
           $table->string('student_status_text',20);
           $table->primary('student_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Student_Status', function (Blueprint $table) {
            Schema::dropIfExists('Student_Status');
        });
    }
}
