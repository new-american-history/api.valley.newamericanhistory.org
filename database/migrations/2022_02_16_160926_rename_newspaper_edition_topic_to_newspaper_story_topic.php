<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNewspaperEditionTopicToNewspaperStoryTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newspaper_edition_topic', function (Blueprint $table) {
            $table->dropForeign(['newspaper_edition_id']);
            $table->renameColumn('newspaper_edition_id', 'newspaper_story_id');

            $table->foreign('newspaper_story_id')
                ->references('id')->on('newspaper_stories')
                ->onDelete('cascade');
        });

        Schema::rename('newspaper_edition_topic', 'newspaper_story_topic');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('newspaper_story_topic', 'newspaper_edition_topic');

        Schema::table('newspaper_edition_topic', function (Blueprint $table) {
            $table->dropForeign(['newspaper_story_id']); //?
            $table->renameColumn('newspaper_story_id', 'newspaper_edition_id');

            $table->foreign('newspaper_edition_id')
                ->references('id')->on('newspaper_editions')
                ->onDelete('cascade');
        });
    }
}
