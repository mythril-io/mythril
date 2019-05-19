<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');

            $table->text('body');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('discussion_id')->unsigned();
            $table->foreign('discussion_id')->references('id')->on('discussions');

            $table->integer('parent_post_id')->unsigned()->nullable();
            $table->foreign('parent_post_id')->references('id')->on('posts');
            
            $table->integer('number')->unsigned()->nullable();

            $table->integer('edit_count')->unsigned()->default(0);

            $table->dateTime('hidden_at')->nullable();
            $table->integer('hidden_user_id')->unsigned()->nullable();

            $table->timestamps();
        });

        // Schema::create('post_parent', function(Blueprint $table)
        // {
        //     $table->integer('post_id')->unsigned()->index();
        //     $table->foreign('post_id')->references('id')->on('posts');

        //     $table->integer('parent_id')->unsigned()->index();
        //     $table->foreign('parent_id')->references('id')->on('posts');

        //     $table->primary(['post_id', 'parent_id']);
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        // Schema::dropIfExists('post_parent');
    }
}
