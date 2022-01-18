<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAgriculturalCensusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agricultural_census', function (Blueprint $table) {
            $table->string('wages_paid')->nullable()->change();
            $table->string('location')->nullable()->after('last_name');
            $table->integer('number_on_page')->unsigned()->nullable()->after('total_land_acres');
            $table->integer('page_number')->unsigned()->nullable()->after('total_land_acres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agricultural_census', function (Blueprint $table) {
            $table->dropColumn('page_number');
            $table->dropColumn('number_on_page');
            $table->dropColumn('location');
            $table->integer('wages_paid')->unsigned()->nullable()->change();
        });
    }
}
