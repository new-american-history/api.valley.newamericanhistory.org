<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldierDossiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soldier_dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('regiment')->nullable();
            $table->string('company')->nullable();
            $table->string('transfer_company')->nullable();

            $table->date('birthday')->nullable();
            $table->text('personal_info')->nullable();
            $table->string('physical_description')->nullable();
            $table->string('image_file')->nullable();
            $table->string('prewar_life')->nullable();
            $table->string('postwar_life')->nullable();

            $table->date('enlisted_date')->nullable();
            $table->string('enlisted_location')->nullable();
            $table->integer('enlisted_age')->unsigned()->nullable();
            $table->string('enlisted_occupation')->nullable();
            $table->string('enlisted_rank')->nullable();
            $table->string('conscript_or_substitute')->nullable();

            $table->string('promotions')->nullable();
            $table->text('transfers')->nullable();
            $table->text('muster_record')->nullable();
            $table->string('hospital_record')->nullable();
            $table->text('notes')->nullable();

            $table->date('death_date')->nullable();
            $table->string('death_location')->nullable();
            $table->string('cause_of_death')->nullable();
            $table->string('burial_location')->nullable();
            $table->text('epitaph')->nullable();

            $table->string('awol_summary')->nullable();
            $table->date('awol_date')->nullable();
            $table->string('captured_summary')->nullable();
            $table->date('captured_date')->nullable();
            $table->string('deserted_summary')->nullable();
            $table->date('deserted_date')->nullable();
            $table->string('died_of_disease_summary')->nullable();
            $table->date('died_of_disease_date')->nullable();
            $table->string('died_of_wounds_summary')->nullable();
            $table->date('died_of_wounds_date')->nullable();
            $table->string('discharged_summary')->nullable();
            $table->date('discharged_date')->nullable();
            $table->string('kia_summary')->nullable();
            $table->date('kia_date')->nullable();
            $table->string('kia_location')->nullable();
            $table->string('mia_summary')->nullable();
            $table->date('mia_date')->nullable();
            $table->string('paroled_summary')->nullable();
            $table->date('paroled_date')->nullable();
            $table->string('pow_summary')->nullable();
            $table->date('pow_date')->nullable();
            $table->string('wia_summary')->nullable();
            $table->date('wia_date')->nullable();

            $table->integer('1860_census_dwelling_number')->unsigned()->nullable();
            $table->integer('1860_census_family_number')->unsigned()->nullable();
            $table->integer('1860_census_page_number')->unsigned()->nullable();
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
        Schema::dropIfExists('soldier_dossiers');
    }
}
