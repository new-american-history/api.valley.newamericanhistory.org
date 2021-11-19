<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRecordTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_records_augusta', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_name')->nullable();

            $table->float('acres', 5, 2)->unsigned()->nullable();
            $table->float('rods', 5, 2)->unsigned()->nullable();
            $table->float('poles', 5, 2)->unsigned()->nullable();
            $table->string('residence')->nullable();
            $table->string('estate')->nullable();
            $table->string('lot_number')->nullable();
            $table->integer('building_value')->unsigned()->nullable();
            $table->float('lot_building_value', 7, 2)->unsigned()->nullable();
            $table->float('tax_amount', 5, 2)->unsigned()->nullable();
            $table->float('city_tax_amount', 5, 2)->unsigned()->nullable();

            $table->string('census_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('tax_records_franklin', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_name')->nullable();
            $table->string('ward')->nullable();
            $table->string('occupation')->nullable();
            $table->string('occupation_value')->nullable();

            $table->float('county_tax_amount')->unsigned()->nullable();
            $table->float('state_tax_amount')->unsigned()->nullable();
            $table->float('state_personal_tax_amount')->unsigned()->nullable();
            $table->string('military_fine')->nullable();

            $table->float('seated_acres', 4, 1)->unsigned()->nullable();
            $table->integer('value_per_seated_acre')->unsigned()->nullable();
            $table->integer('seated_land_value')->unsigned()->nullable();
            $table->float('unseated_acres', 4, 2)->unsigned()->nullable();
            $table->integer('unseated_land_value')->unsigned()->nullable();
            $table->float('number_seated_lots', 6, 3)->unsigned()->nullable();
            $table->float('seated_lot_value', 6, 1)->unsigned()->nullable();
            $table->float('number_unseated_lots', 6, 3)->unsigned()->nullable();
            $table->float('unseated_lot_value', 6, 1)->unsigned()->nullable();

            $table->string('carriages_count')->nullable();
            $table->string('carriages_tax_amount')->nullable();
            $table->string('cattle_count')->nullable();
            $table->string('cattle_tax_amount')->nullable();
            $table->string('furniture_tax_amount')->nullable();
            $table->string('horses_count')->nullable();
            $table->string('horses_tax_amount')->nullable();
            $table->string('money_tax_amount')->nullable();
            $table->string('watches_count')->nullable();
            $table->string('watches_tax_amount')->nullable();

            $table->string('census_notes')->nullable();
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
        Schema::dropIfExists('tax_records_franklin');
        Schema::dropIfExists('tax_records_augusta');
    }
}
