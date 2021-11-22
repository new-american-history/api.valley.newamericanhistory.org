<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCivilWarImagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civil_war_image_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('civil_war_images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('file_name')->nullable();
            $table->string('date')->nullable();
            $table->text('description')->nullable();

            $table->string('artist')->nullable();
            $table->string('image_type')->nullable();
            $table->string('person_name')->nullable();
            $table->string('location')->nullable();
            $table->string('regiment')->nullable();
            $table->bigInteger('subject_id')->unsigned()->nullable();

            $table->string('original_source')->nullable();
            $table->string('contributing_source')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')
                ->references('id')->on('civil_war_image_subjects')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('civil_war_images');
        Schema::dropIfExists('civil_war_image_subjects');
    }
}
