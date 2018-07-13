<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Data_Status', function (Blueprint $table) {
                      $table->unsignedTinyInteger('data_status');
                      $table->string('data_status_text',20);
                      $table->primary('data_status');
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
        Schema::table('Data_Status', function (Blueprint $table) {
            Schema::dropIfExists('Data_Status');
        });
    }
}
