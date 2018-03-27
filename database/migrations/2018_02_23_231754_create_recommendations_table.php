<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('game_id')->unsigned()->index();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->integer('release_id')->unsigned()->index();
            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');

            $table->integer('second_game_id')->unsigned()->index();
            $table->foreign('second_game_id')->references('id')->on('games')->onDelete('cascade');
            $table->integer('second_release_id')->unsigned()->index();
            $table->foreign('second_release_id')->references('id')->on('releases')->onDelete('cascade');

            $table->text('content');
            
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
        Schema::dropIfExists('recommendations');
    }
}
