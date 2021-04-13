<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionAndAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_and_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('poll_id')->constrained('poll');
            $table->string('question');
            $table->string('answer');
            $table->boolean('true_answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_and_answer');
    }
}
