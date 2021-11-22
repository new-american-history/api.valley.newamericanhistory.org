<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFireInsurancePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fire_insurance_policies', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->string('policy_number')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image_file')->nullable();
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
        Schema::dropIfExists('fire_insurance_policies');
    }
}
