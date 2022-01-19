<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturingCensusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturing_census_materials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manufacturing_census_id')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->string('quantity')->nullable();
            $table->integer('value')->unsigned()->nullable();
        });

        Schema::create('manufacturing_census_production', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manufacturing_census_id')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->string('quantity')->nullable();
            $table->integer('value')->unsigned()->nullable();
        });

        Schema::create('manufacturing_census', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('name')->nullable();
            $table->string('business')->nullable();
            $table->string('business_class')->nullable();
            $table->string('location')->nullable();
            $table->integer('months_active')->unsigned()->nullable();
            $table->integer('capital_invested')->unsigned()->nullable();

            $table->string('power')->nullable();
            $table->string('horsepower')->nullable();
            $table->string('machines')->nullable();
            $table->integer('number_of_machines')->unsigned()->nullable();

            $table->float('female_hands', 4, 1)->unsigned()->nullable();
            $table->float('male_hands', 4, 1)->unsigned()->nullable();
            $table->float('child_hands', 4, 1)->unsigned()->nullable();
            $table->integer('female_wages')->unsigned()->nullable();
            $table->integer('male_wages')->unsigned()->nullable();
            $table->integer('total_wages')->unsigned()->nullable();

            $table->integer('page_number')->unsigned()->nullable();
            $table->integer('number_on_page')->unsigned()->nullable();

            $table->string('notes')->nullable();
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
        Schema::dropIfExists('manufacturing_census');
        Schema::dropIfExists('manufacturing_census_production');
        Schema::dropIfExists('manufacturing_census_materials');
    }
}
