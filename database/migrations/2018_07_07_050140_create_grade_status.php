<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Grade_Status', function (Blueprint $table) {
                      $table->unsignedTinyInteger('grade_status');
                      $table->string('grade_status_text',20);
                      $table->primary('grade_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Grade_Status', function (Blueprint $table) {
            Schema::dropIfExists('Grade_Status');
        });
    }
}
