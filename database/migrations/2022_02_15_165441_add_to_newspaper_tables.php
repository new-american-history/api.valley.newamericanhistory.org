<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToNewspaperTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newspaper_editions', function (Blueprint $table) {
            $table->string('source_file')->nullable()->after('pdf');
            $table->string('weekday')->nullable()->after('date');

            $table->text('headline')->nullable()->change();
        });

        Schema::table('newspaper_stories', function (Blueprint $table) {
            $table->text('headline')->nullable()->change();
            $table->longText('body')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newspaper_stories', function (Blueprint $table) {
            $table->text('body')->nullable()->change();
            $table->string('headline')->nullable()->change();
        });

        Schema::table('newspaper_editions', function (Blueprint $table) {
            $table->string('headline')->nullable()->change();

            $table->dropColumn('weekday');
            $table->dropColumn('source_file');
        });
    }
}
