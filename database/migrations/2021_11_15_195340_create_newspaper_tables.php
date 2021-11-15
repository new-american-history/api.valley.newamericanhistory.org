<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewspaperTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newspapers', function (Blueprint $table) {
            $table->id();
            $table->string('county')->nullable();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('frequency')->nullable();
            $table->timestamps();
        });

        Schema::create('newspaper_editions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('newspaper_id')->unsigned();
            $table->date('date')->nullable();
            $table->string('headline')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();

            $table->foreign('newspaper_id')
                ->references('id')->on('newspapers')
                ->onDelete('cascade');
        });

        Schema::create('newspaper_pages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('newspaper_edition_id')->unsigned();
            $table->integer('number')->unsigned();
            $table->text('description')->nullable();

            $table->foreign('newspaper_edition_id')
                ->references('id')->on('newspaper_editions')
                ->onDelete('cascade');
        });

        Schema::create('newspaper_stories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('newspaper_page_id')->unsigned();
            $table->integer('weight')->unsigned();
            $table->string('column')->nullable();
            $table->string('type')->nullable();

            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->text('body')->nullable();
            $table->text('origin')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('trailer')->nullable();
            $table->text('commentary')->nullable();

            $table->foreign('newspaper_page_id')
                ->references('id')->on('newspaper_pages')
                ->onDelete('cascade');
        });

        Schema::create('newspaper_names', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('newspaper_story_id')->unsigned();
            $table->integer('weight')->unsigned();

            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();

            $table->foreign('newspaper_story_id')
                ->references('id')->on('newspaper_stories')
                ->onDelete('cascade');
        });

        Schema::create('newspaper_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('chapter')->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();

            $table->foreign('parent_id')
                ->references('id')->on('newspaper_topics')
                ->onDelete('set null');
        });

        Schema::create('newspaper_edition_topic', function (Blueprint $table) {
            $table->bigInteger('newspaper_edition_id')->unsigned();
            $table->bigInteger('newspaper_topic_id')->unsigned();

            $table->foreign('newspaper_edition_id')
                ->references('id')->on('newspaper_editions')
                ->onDelete('cascade');
            $table->foreign('newspaper_topic_id')
                ->references('id')->on('newspaper_topics')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newspaper_edition_topic');
        Schema::dropIfExists('newspaper_topics');
        Schema::dropIfExists('newspaper_names');
        Schema::dropIfExists('newspaper_stories');
        Schema::dropIfExists('newspaper_pages');
        Schema::dropIfExists('newspaper_editions');
        Schema::dropIfExists('newspapers');
    }
}
