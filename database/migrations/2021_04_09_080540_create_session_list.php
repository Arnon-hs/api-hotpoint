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
            $table->unsignedbigInteger('session_id');
            $table->foreignId('speaker_id')->references('speaker_id')->on('speakers');
            $table->string('sort');
            $table->string('name');
            $table->date('sessiondate')->nullable();
            $table->time('starttime')->nullable();
            $table->time('endtime')->nullable();
            $table->unsignedbigInteger('location_id')->nullable();
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
