<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChambersburgClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chambersburg_claims', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->integer('claim_number')->unsigned()->nullable();
            $table->date('claim_date')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('transcription_name')->nullable();
            $table->string('race')->nullable();
            $table->string('sex')->nullable();

            $table->float('claim_total', 7, 2)->unsigned()->nullable();
            $table->float('personal_property', 7, 2)->unsigned()->nullable();
            $table->float('real_property', 7, 2)->unsigned()->nullable();
            $table->string('items')->nullable();
            $table->float('amount_awarded', 7, 2)->unsigned()->nullable();
            $table->float('amount_received', 7, 2)->unsigned()->nullable();
            $table->string('notes')->nullable();

            $table->string('building_number')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name')->nullable();
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
        Schema::dropIfExists('chambersburg_claims');
    }
}
