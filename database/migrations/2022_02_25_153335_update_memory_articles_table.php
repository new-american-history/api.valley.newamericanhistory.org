<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMemoryArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memory_articles', function (Blueprint $table) {
            $table->dropColumn('year');

            $table->date('date')->nullable()->after('title');
            $table->text('title')->nullable()->change();
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
        Schema::table('memory_articles', function (Blueprint $table) {
            $table->dropColumn('date');

            $table->text('body')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->integer('year')->unsigned()->nullable()->after('title');
        });
    }
}
