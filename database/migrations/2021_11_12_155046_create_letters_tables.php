<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLettersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('county')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->date('date')->nullable();
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();

            $table->string('recipient')->nullable();
            $table->string('dateline')->nullable();
            $table->string('location')->nullable();
            $table->string('opening_salutation')->nullable();
            $table->text('body')->nullable();
            $table->string('closing_salutation')->nullable();
            $table->string('signed')->nullable();
            $table->text('postscript')->nullable();

            $table->text('keywords')->nullable();
            $table->string('source_file')->nullable();
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unsigned();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('letter_note', function (Blueprint $table) {
            $table->bigInteger('letter_id')->unsigned();
            $table->bigInteger('note_id')->unsigned();

            $table->foreign('letter_id')
                ->references('id')->on('letters')
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
        Schema::dropIfExists('letter_note');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('letters');
    }
}
