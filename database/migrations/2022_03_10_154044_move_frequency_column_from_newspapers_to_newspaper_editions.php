<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveFrequencyColumnFromNewspapersToNewspaperEditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newspaper_editions', function (Blueprint $table) {
            $table->string('frequency')->nullable()->after('date');
        });

        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropColumn('frequency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newspapers', function (Blueprint $table) {
            $table->string('frequency')->nullable()->after('state');
        });

        Schema::table('newspaper_editions', function (Blueprint $table) {
            $table->dropColumn('frequency');
        });
    }
}
