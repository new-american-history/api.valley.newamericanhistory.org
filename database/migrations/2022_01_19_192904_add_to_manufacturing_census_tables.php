<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToManufacturingCensusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufacturing_census', function (Blueprint $table) {
            $table->integer('data_id')->unsigned()->nullable()->after('number_on_page');
        });

        Schema::table('manufacturing_census_materials', function (Blueprint $table) {
            $table->integer('census_data_id')->unsigned()->nullable()->after('value');
            $table->foreign('manufacturing_census_id')
                ->references('id')->on('manufacturing_census')
                ->onDelete('set null');
        });

        Schema::table('manufacturing_census_products', function (Blueprint $table) {
            $table->integer('census_data_id')->unsigned()->nullable()->after('value');
            $table->foreign('manufacturing_census_id')
                ->references('id')->on('manufacturing_census')
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
        Schema::table('manufacturing_census_products', function (Blueprint $table) {
            $table->dropForeign(['manufacturing_census_id']);
            $table->dropColumn('census_data_id');
        });

        Schema::table('manufacturing_census_materials', function (Blueprint $table) {
            $table->dropForeign(['manufacturing_census_id']);
            $table->dropColumn('census_data_id');
        });

        Schema::table('manufacturing_census', function (Blueprint $table) {
            $table->dropColumn('data_id');
        });
    }
}
