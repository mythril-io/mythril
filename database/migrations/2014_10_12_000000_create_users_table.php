<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();

            $table->text('about_me')->nullable();
            $table->string('timezone')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('location')->nullable();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();

            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('discord')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitch')->nullable();
            $table->string('deviant_art')->nullable();
            $table->string('reddit')->nullable();
            $table->string('patreon')->nullable();
            $table->string('tumblr')->nullable();
            $table->string('battle_net')->nullable();
            $table->string('steam')->nullable();
            $table->string('playstation')->nullable();
            $table->string('nintendo_switch')->nullable();
            $table->string('xbox')->nullable();
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
        Schema::dropIfExists('users');
    }
}
