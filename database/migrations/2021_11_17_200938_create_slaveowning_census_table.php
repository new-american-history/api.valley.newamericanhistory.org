<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaveowningCensusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slaveowning_census', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('year')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('location')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('employer_location')->nullable();

            $table->integer('total_slaves')->unsigned()->nullable();
            $table->integer('black_slaves')->unsigned()->nullable();
            $table->integer('mulatto_slaves')->unsigned()->nullable();
            $table->integer('female_slaves')->unsigned()->nullable();
            $table->integer('male_slaves')->unsigned()->nullable();

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
        Schema::dropIfExists('slaveowning_census');
    }
}
