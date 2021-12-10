<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgriculturalCensusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agricultural_census', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();

            $table->integer('wages_paid')->unsigned()->nullable();

            $table->integer('farm_value')->unsigned()->nullable();
            $table->integer('farm_implements_value')->unsigned()->nullable();
            $table->integer('forest_products_value')->unsigned()->nullable();
            $table->integer('home_manufactures_value')->unsigned()->nullable();
            $table->integer('livestock_value')->unsigned()->nullable();
            $table->integer('market_garden_produce_value')->unsigned()->nullable();
            $table->integer('orchard_products_value')->unsigned()->nullable();
            $table->integer('slaughtered_animals_value')->unsigned()->nullable();
            $table->integer('total_value')->unsigned()->nullable();

            $table->integer('barley_bushels')->unsigned()->nullable();
            $table->integer('beeswax_pounds')->unsigned()->nullable();
            $table->integer('buckwheat_bushels')->unsigned()->nullable();
            $table->integer('butter_pounds')->unsigned()->nullable();
            $table->integer('cane_sugar_hogsheads')->unsigned()->nullable();
            $table->integer('cheese_pounds')->unsigned()->nullable();
            $table->integer('clover_seed_bushels')->unsigned()->nullable();
            $table->integer('corn_bushels')->unsigned()->nullable();
            $table->integer('cotton_bales')->unsigned()->nullable();
            $table->integer('cows')->unsigned()->nullable();
            $table->integer('flax_pounds')->unsigned()->nullable();
            $table->integer('flax_seed_bushels')->unsigned()->nullable();
            $table->integer('grass_seed_bushels')->unsigned()->nullable();
            $table->integer('hay_tons')->unsigned()->nullable();
            $table->integer('hemp_tons')->unsigned()->nullable();
            $table->integer('honey_pounds')->unsigned()->nullable();
            $table->integer('hops_pounds')->unsigned()->nullable();
            $table->integer('horses')->unsigned()->nullable();
            $table->integer('irish_potatoes_bushels')->unsigned()->nullable();
            $table->integer('maple_sugar_pounds')->unsigned()->nullable();
            $table->integer('milk_gallons')->unsigned()->nullable();
            $table->integer('molasses_gallons')->unsigned()->nullable();
            $table->integer('mules_and_asses')->unsigned()->nullable();
            $table->integer('oats_bushels')->unsigned()->nullable();
            $table->integer('oxen')->unsigned()->nullable();
            $table->integer('peas_and_beans_bushels')->unsigned()->nullable();
            $table->integer('rice_pounds')->unsigned()->nullable();
            $table->integer('rye_bushels')->unsigned()->nullable();
            $table->integer('sheep')->unsigned()->nullable();
            $table->integer('silk_cocoons_pounds')->unsigned()->nullable();
            $table->integer('spring_wheat_bushels')->unsigned()->nullable();
            $table->integer('sweet_potatoes_bushels')->unsigned()->nullable();
            $table->integer('swine')->unsigned()->nullable();
            $table->integer('tobacco_pounds')->unsigned()->nullable();
            $table->integer('wheat_bushels')->unsigned()->nullable();
            $table->integer('wine_gallons')->unsigned()->nullable();
            $table->integer('winter_wheat_bushels')->unsigned()->nullable();
            $table->integer('wool_pounds')->unsigned()->nullable();
            $table->integer('other_cattle')->unsigned()->nullable();
            $table->integer('total_animals')->unsigned()->nullable();
            $table->integer('total_grain_bushels')->unsigned()->nullable();

            $table->integer('improved_land_acres')->unsigned()->nullable();
            $table->integer('unimproved_land_acres')->unsigned()->nullable();
            $table->integer('woodland_acres')->unsigned()->nullable();
            $table->integer('other_unimproved_land_acres')->unsigned()->nullable();
            $table->integer('total_land_acres')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agricultural_census');
    }
}
