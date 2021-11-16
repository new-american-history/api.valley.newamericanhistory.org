<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoryArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memory_articles', function (Blueprint $table) {
            $table->id();
            $table->string('county')->nullable();
            $table->integer('year')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->text('summary')->nullable();
            $table->text('body')->nullable();

            $table->text('keywords')->nullable();
            $table->string('source_file')->nullable();
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
        Schema::dropIfExists('memory_articles');
    }
}
