<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_records', function (Blueprint $table) {
            $table->id();
            $table->string('county')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->date('date')->nullable();
            $table->text('summary')->nullable();

            $table->string('headline')->nullable();
            $table->string('recipient')->nullable();
            $table->string('dateline')->nullable();
            $table->string('location')->nullable();

            $table->string('opening_salutation')->nullable();
            $table->text('body')->nullable();
            $table->string('closing_salutation')->nullable();
            $table->string('signed')->nullable();

            $table->text('keywords')->nullable();
            $table->string('source_file')->nullable();
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
        Schema::dropIfExists('official_records');
    }
}
