<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('location_id');
            $table->string('name');
            $table->string('videoPath');
            $table->string('videoPathEn');
            $table->bigInteger('chat_id');
            $table->bigInteger('chatWidget_id');
        });

//        Schema::table('session_list', function (Blueprint $table){
////            $table->unsignedBigInteger('location_id')->nullable(false)->change();
////            $table->bigIncrements('location_id');
//            $table->foreignID('location_id')->change()->references('location_id')->on('locations');
////            $table->foreignId('location_id')->change()->constrained('poll');
//        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
