<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWagesPaidToIntegerInAgriculturalCensusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agricultural_census', function (Blueprint $table) {
            $table->integer('wages_paid')->unsigned()->nullable()->change();
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
            $table->string('wages_paid')->nullable()->change();
        });
    }
}
