<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->string('county')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->date('date')->nullable();

            $table->text('keywords')->nullable();
            $table->string('source_file')->nullable();
            $table->timestamps();
        });

        Schema::create('diary_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('diary_id')->unsigned();
            $table->integer('weight')->unsigned();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('headline')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();

            $table->foreign('diary_id')
                ->references('id')->on('diaries')
                ->onDelete('cascade');
        });


        Schema::create('diary_note', function (Blueprint $table) {
            $table->bigInteger('diary_id')->unsigned();
            $table->bigInteger('note_id')->unsigned();

            $table->foreign('diary_id')
                ->references('id')->on('diaries')
                ->onDelete('cascade');
            $table->foreign('note_id')
                ->references('id')->on('notes')
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
        Schema::dropIfExists('diary_note');
        Schema::dropIfExists('diary_entries');
        Schema::dropIfExists('diaries');
    }
}
