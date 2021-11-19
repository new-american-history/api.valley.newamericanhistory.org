<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegimentalMovementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regiments', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->string('state');
            $table->string('name')->nullable();
            $table->string('name_in_dossiers')->nullable();
        });

        Schema::create('regimental_movements', function (Blueprint $table) {
            $table->id();
            $table->string('battle_name')->nullable();
            $table->string('battle_state')->nullable();
            $table->date('battle_start_date')->nullable();
            $table->date('battle_end_date')->nullable();
            $table->text('summary')->nullable();

            $table->string('commander')->nullable();
            $table->string('corps')->nullable();
            $table->string('division')->nullable();
            $table->string('brigade')->nullable();
            $table->bigInteger('regiment_id')->unsigned()->nullable();
            $table->string('regiment_strength')->nullable();

            $table->string('killed')->nullable();
            $table->string('wounded')->nullable();
            $table->string('missing')->nullable();

            $table->text('local_weather')->nullable();
            $table->text('georgetown_weather')->nullable();
            $table->timestamps();

            $table->foreign('regiment_id')
                ->references('id')->on('regiments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regimental_movements');
        Schema::dropIfExists('regiments');
    }
}
