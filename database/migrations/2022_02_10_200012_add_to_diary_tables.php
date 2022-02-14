<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToDiaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diaries', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('author');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->string('headline')->nullable()->after('number');
            $table->renameColumn('content', 'body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->renameColumn('body', 'content');
            $table->dropColumn('headline');
        });

        Schema::table('diaries', function (Blueprint $table) {
            $table->dropColumn('bio');
        });
    }
}
