<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValleyIdColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('battlefield_correspondence', function (Blueprint $table) {
            $table->string('valley_id')->after('id');
        });

        Schema::table('diaries', function (Blueprint $table) {
            $table->string('valley_id')->after('id');
        });

        Schema::table('letters', function (Blueprint $table) {
            $table->string('valley_id')->after('id');
        });

        Schema::table('memory_articles', function (Blueprint $table) {
            $table->string('valley_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memory_articles', function (Blueprint $table) {
            $table->dropColumn('valley_id');
        });

        Schema::table('letters', function (Blueprint $table) {
            $table->dropColumn('valley_id');
        });

        Schema::table('diaries', function (Blueprint $table) {
            $table->dropColumn('valley_id');
        });

        Schema::table('battlefield_correspondence', function (Blueprint $table) {
            $table->dropColumn('valley_id');
        });
    }
}
