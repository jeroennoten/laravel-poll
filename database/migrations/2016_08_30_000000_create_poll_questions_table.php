<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePollQuestionsTable extends Migration {

    public function up()
    {
        Schema::create('poll_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('poll_questions');
    }

}
