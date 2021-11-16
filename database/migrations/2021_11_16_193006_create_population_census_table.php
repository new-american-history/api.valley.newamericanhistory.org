<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopulationCensusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('population_census', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();

            $table->float('age')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('race')->nullable();
            $table->string('sex')->nullable();
            $table->string('occupation_code')->nullable();
            $table->string('occupation')->nullable();

            $table->integer('dwelling_number')->unsigned()->nullable();
            $table->integer('family_number')->unsigned()->nullable();
            $table->integer('head_number')->unsigned()->nullable();

            $table->boolean('attended_school')->nullable();
            $table->boolean('cannot_read')->nullable();
            $table->boolean('cannot_write')->nullable();
            $table->string('disability')->nullable();
            $table->boolean('father_foreign_born')->nullable();
            $table->boolean('mother_foreign_born')->nullable();
            $table->boolean('male_citizen')->nullable();
            $table->boolean('male_citizen_novote')->nullable();
            $table->boolean('married_within_the_year')->nullable();
            $table->integer('marriage_month')->unsigned()->nullable();
            $table->integer('birth_month')->unsigned()->nullable();
            $table->integer('personal_estate_value')->unsigned()->nullable();
            $table->integer('real_estate_value')->unsigned()->nullable();

            $table->date('date_taken')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('post_office')->nullable();
            $table->integer('page_number')->unsigned()->nullable();

            $table->text('notes')->nullable();
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
        Schema::dropIfExists('population_census');
    }
}
