<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Teacher_Status', function (Blueprint $table) {
           $table->unsignedTinyInteger('teacher_status');
           $table->string('teacher_status_text',20);
           $table->primary('teacher_status');
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Teacher_Status', function (Blueprint $table) {
            Schema::dropIfExists('Teacher_Status');
        });
    }
}
