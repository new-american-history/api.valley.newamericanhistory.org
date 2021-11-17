<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVeteranCensusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veteran_census', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('location')->nullable();
            $table->string('post_office')->nullable();
            $table->string('family_number')->nullable();
            $table->integer('house_number')->unsigned()->nullable();
            $table->string('widow_name')->nullable();

            $table->date('enlistment_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('length_of_service')->nullable();

            $table->string('company')->nullable();
            $table->string('disability')->nullable();
            $table->string('rank')->nullable();
            $table->string('regiment')->nullable();
            $table->integer('superior_district_number')->unsigned()->nullable();

            $table->text('remarks')->nullable();
            $table->string('other_info')->nullable();

            $table->string('enumerator')->nullable();
            $table->string('enumerator_district')->nullable();
            $table->integer('page_number')->unsigned()->nullable();
            $table->integer('number_on_page')->unsigned()->nullable();
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
        Schema::dropIfExists('veteran_census');
    }
}
