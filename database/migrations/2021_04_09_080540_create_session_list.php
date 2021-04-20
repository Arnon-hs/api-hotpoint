<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_list', function (Blueprint $table) {
            $table->unsignedBigInteger('sessionid');
            $table->unsignedBigInteger('questionid');
            $table->string('name');
            $table->string('desc')->nullable();
            $table->date('sessiondate')->nullable();
            $table->time('starttime')->nullable();
            $table->time('endtime')->nullable();
            $table->integer('sort');
            $table->string('location_name')->nullable();
            $table->bigInteger('locationid')->nullable();
            $table->boolean('openflag');
            $table->boolean('visible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_list');
    }
}
