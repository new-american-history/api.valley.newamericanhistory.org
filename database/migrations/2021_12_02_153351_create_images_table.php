<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('alt')->nullable();
            $table->integer('width')->unsigned()->nullable();
            $table->integer('height')->unsigned()->nullable();
            $table->string('derivative_type')->nullable();
            $table->bigInteger('source_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('source_id')
                ->references('id')->on('images')
                ->onDelete('cascade');
        });

        Schema::create('diary_image', function (Blueprint $table) {
            $table->bigInteger('diary_id')->unsigned();
            $table->bigInteger('image_id')->unsigned();
            $table->integer('weight')->unsigned();

            $table->foreign('diary_id')
                ->references('id')->on('diaries')
                ->onDelete('cascade');
            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('cascade');
        });

        Schema::create('letter_image', function (Blueprint $table) {
            $table->bigInteger('letter_id')->unsigned();
            $table->bigInteger('image_id')->unsigned();
            $table->integer('weight')->unsigned();

            $table->foreign('letter_id')
                ->references('id')->on('letters')
                ->onDelete('cascade');
            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('cascade');
        });

        Schema::table('chambersburg_claims', function (Blueprint $table) {
            $table->dropColumn('file_name');
            $table->string('image_heading')->nullable()->after('description');
            $table->string('image_title')->nullable()->after('description');
            $table->bigInteger('image_id')->unsigned()->nullable()->after('description');

            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });

        Schema::table('civil_war_images', function (Blueprint $table) {
            $table->dropColumn('file_name');
            $table->bigInteger('image_id')->unsigned()->nullable()->after('title');

            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });

        Schema::table('fire_insurance_policies', function (Blueprint $table) {
            $table->dropColumn('image_file');
            $table->bigInteger('image_id')->unsigned()->nullable()->after('description');

            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });

        Schema::table('soldier_dossiers', function (Blueprint $table) {
            $table->dropColumn('image_file');
            $table->bigInteger('image_id')->unsigned()->nullable()->after('physical_description');

            $table->foreign('image_id')
                ->references('id')->on('images')
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
        Schema::table('soldier_dossiers', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
            $table->string('image_file')->nullable()->after('physical_description');
        });

        Schema::table('fire_insurance_policies', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
            $table->string('image_file')->nullable()->after('description');
        });

        Schema::table('civil_war_images', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
            $table->string('file_name')->nullable()->after('title');
        });

        Schema::table('chambersburg_claims', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
            $table->dropColumn('image_title');
            $table->dropColumn('image_heading');
            $table->string('file_name')->nullable()->after('description');
        });

        Schema::dropIfExists('letter_image');
        Schema::dropIfExists('diary_image');
        Schema::dropIfExists('images');
    }
}
