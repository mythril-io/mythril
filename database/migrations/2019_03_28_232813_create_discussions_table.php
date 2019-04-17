<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->string('slug', 100)->unique();
            $table->text('body');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->integer('view_count')->unsigned()->default(0)->index();
            $table->integer('like_count')->unsigned()->default(0)->index();
            $table->integer('post_count')->unsigned()->default(0)->index();
            $table->integer('user_count')->unsigned()->default(0)->index();
            $table->integer('edit_count')->unsigned()->default(0);

            $table->integer('pinned')->unsigned()->default(0);

                // $table->integer('post_number_index')->unsigned()->default(0);
            $table->integer('first_user_id')->unsigned()->nullable();
            $table->integer('first_post_id')->unsigned()->nullable();
            $table->dateTime('first_posted_at')->nullable();

            $table->integer('last_post_user_id')->unsigned()->nullable();
            $table->integer('last_post_id')->unsigned()->nullable()->index();
            $table->dateTime('last_posted_at')->nullable()->index();
                // $table->integer('last_post_number')->unsigned()->nullable();

            $table->dateTime('hidden_at')->nullable();
            $table->integer('hidden_user_id')->unsigned()->nullable();

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
        Schema::dropIfExists('discussions');
    }
}
