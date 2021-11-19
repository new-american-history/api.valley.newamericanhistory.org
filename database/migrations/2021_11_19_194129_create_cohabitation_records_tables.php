<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohabitationRecordsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohabitation_families', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->date('report_date')->nullable();
            $table->integer('family_id')->unsigned()->unique();
            $table->string('residence')->nullable();
            $table->string('husband_first_name')->nullable();
            $table->string('husband_last_name')->nullable();
            $table->string('wife_first_name')->nullable();
            $table->string('wife_last_name')->nullable();

            $table->integer('husband_age')->unsigned()->nullable();
            $table->integer('wife_age')->unsigned()->nullable();
            $table->string('husband_birthplace')->nullable();
            $table->string('wife_birthplace')->nullable();
            $table->string('husband_occupation')->nullable();
            $table->integer('number_of_children')->unsigned()->nullable();

            $table->string('original_remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('cohabitation_children', function (Blueprint $table) {
            $table->id();
            $table->integer('family_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->float('age', 5, 3)->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->timestamps();

            $table->foreign('family_id')
                ->references('family_id')->on('cohabitation_families')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cohabitation_children');
        Schema::dropIfExists('cohabitation_families');
    }
}
