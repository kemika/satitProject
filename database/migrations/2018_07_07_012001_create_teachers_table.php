<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Teachers', function (Blueprint $table) {
            $table->string('teacher_id',100);
            $table->string('firstname',100);
            $table->string('lastname',100);
            $table->unsignedTinyInteger('teacher_status');
            $table->primary('teacher_id');
            $table->foreign('teacher_status')
                  ->references('teacher_status')
                  ->on('Teacher_Status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Teachers');
    }
}
