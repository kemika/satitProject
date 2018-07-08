<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBehaviorTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Behavior_Types', function (Blueprint $table) {
                      $table->unsignedTinyInteger('behavior_type');
                      $table->string('behavior_type_text',100);
                      $table->primary('behavior_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Behavior_Types', function (Blueprint $table) {
            Schema::dropIfExists('Data_Status');
        });
    }
}
