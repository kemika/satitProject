<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Students', function (Blueprint $table) {
            $table->string('student_id',50);
            $table->string('firstname',100);
            $table->string('lastname',100);
            $table->unsignedTinyInteger('student_status');
            $table->primary('student_id');
             $table->timestamps();
            $table->foreign('student_status')
                  ->references('student_status')
                  ->on('Student_Status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Students');
    }
}
