<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChurchRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('church_records', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->string('church_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->date('date')->nullable();
            $table->string('record_type')->nullable();

            $table->string('clergy')->nullable();
            $table->string('location')->nullable();
            $table->string('witness')->nullable();
            $table->string('age')->nullable();
            $table->string('family')->nullable();
            $table->string('race')->nullable();
            $table->string('sex')->nullable();

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
        Schema::dropIfExists('church_records');
    }
}
