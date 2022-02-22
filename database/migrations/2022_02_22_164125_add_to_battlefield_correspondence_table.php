<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToBattlefieldCorrespondenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('battlefield_correspondence', function (Blueprint $table) {
            $table->text('postscript')->nullable()->after('signed');
            $table->text('headline')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('battlefield_correspondence', function (Blueprint $table) {
            $table->string('headline')->nullable()->change();
            $table->dropColumn('postscript');
        });
    }
}
