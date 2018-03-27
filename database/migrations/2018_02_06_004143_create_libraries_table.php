<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('game_id')->unsigned()->index();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->integer('release_id')->unsigned()->index();
            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->integer('play_status_id')->unsigned();
            $table->foreign('play_status_id')->references('id')->on('play_statuses');

            $table->tinyInteger('score')->unsigned()->nullable();
            $table->boolean('own')->default(true);
            $table->boolean('digital')->default(false);
            $table->integer('hours')->unsigned()->nullable();
            $table->string('notes')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('libraries');
    }
}
