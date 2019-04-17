<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug', 100)->unique();
            $table->string('colour');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('tags')->onDelete('set null');
            $table->integer('order')->unsigned()->nullable();	
            $table->boolean('is_restricted')->default(0);
            $table->boolean('is_hidden')->default(0);
            $table->integer('discussions_count')->unsigned()->default(0);

            $table->integer('last_posted_user_id')->unsigned()->nullable();
            $table->foreign('last_posted_user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('last_posted_discussion_id')->unsigned()->nullable();
            $table->foreign('last_posted_discussion_id')->references('id')->on('discussions')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('discussion_tag', function(Blueprint $table)
        {
            $table->integer('discussion_id')->unsigned()->index();
            $table->foreign('discussion_id')->references('id')->on('discussions');

            $table->integer('tag_id')->unsigned()->index();
            $table->foreign('tag_id')->references('id')->on('tags');

            $table->primary(['discussion_id', 'tag_id']);

            $table->timestamps();
        });

        DB::table('tags')->insert([
            ['name' => 'General', 'slug' => 'general', 'colour' => 'is-light', 'order' => 1],
            ['name' => 'Games', 'slug' => 'games', 'colour' => 'is-primary', 'order' => 2],
            ['name' => 'Hardware', 'slug' => 'hardware', 'colour' => 'has-background-grey', 'order' => 3],
            ['name' => 'Anime', 'slug' => 'anime', 'colour' => 'is-danger', 'order' => 4],
            ['name' => 'Music', 'slug' => 'music', 'colour' => 'is-warning', 'order' => 5],
            ['name' => 'Movies', 'slug' => 'movies', 'colour' => 'is-success', 'order' => 6],
            ['name' => 'Site Updates', 'slug' => 'site-updates', 'colour' => 'is-info', 'order' => 7],
            ['name' => 'Site Feedback', 'slug' => 'site-feedback', 'colour' => 'has-background-grey-light', 'order' => 8],
            ['name' => 'Bugs', 'slug' => 'bugs', 'colour' => 'has-background-grey-light', 'order' => 9]
        ]);

        DB::table('tags')->insert([
            ['name' => 'Release Discussion', 'slug' => 'release-discussion', 'colour' => 'is-dark', 'parent_id' => 2, 'order' => 1]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('discussion_tag');
    }
}
