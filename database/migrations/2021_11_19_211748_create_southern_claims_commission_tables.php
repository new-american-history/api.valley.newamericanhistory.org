<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSouthernClaimsCommissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('southern_claims_commission', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->date('date')->nullable();
            $table->text('summary')->nullable();
            $table->text('commission_summary')->nullable();

            $table->text('keywords')->nullable();
            $table->string('source_file')->nullable();
            $table->timestamps();
        });

        Schema::create('southern_claims_commission_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('claim_id')->unsigned()->nullable();
            $table->string('item')->nullable();
            $table->float('amount_claimed', 6, 2)->unsigned()->nullable();
            $table->float('amount_allowed', 6, 2)->unsigned()->nullable();
            $table->float('amount_disallowed', 6, 2)->unsigned()->nullable();
            $table->integer('weight')->unsigned()->nullable();

            $table->foreign('claim_id')
                ->references('id')->on('southern_claims_commission')
                ->onDelete('cascade');
        });

        Schema::create('southern_claims_commission_testimonies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('claim_id')->unsigned()->nullable();
            $table->string('attestant')->nullable();
            $table->text('body')->nullable();
            $table->integer('weight')->unsigned()->nullable();

            $table->foreign('claim_id')
                ->references('id')->on('southern_claims_commission')
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
        Schema::dropIfExists('southern_claims_commission_testimonies');
        Schema::dropIfExists('southern_claims_commission_items');
        Schema::dropIfExists('southern_claims_commission');
    }
}
