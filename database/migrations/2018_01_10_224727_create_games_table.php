<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->text('synopsis');
            $table->string('icon');
            $table->string('banner');
            $table->integer('developer_id')->unsigned();
            $table->foreign('developer_id')->references('id')->on('developers');
            $table->decimal('score', 5, 2)->unsigned()->nullable();
            $table->integer('library_count')->unsigned()->nullable();
            $table->integer('score_rank')->unsigned()->nullable();
            $table->integer('popularity_rank')->unsigned()->nullable();
            $table->integer('trending_page_views')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('games');
    }
}
