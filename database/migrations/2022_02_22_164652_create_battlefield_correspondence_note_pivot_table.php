<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattlefieldCorrespondenceNotePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battlefield_correspondence_note', function (Blueprint $table) {
            $table->bigInteger('battlefield_correspondence_id')->unsigned();
            $table->bigInteger('note_id')->unsigned();

            $table->foreign('battlefield_correspondence_id', 'battlefield_correspondence_note_bc_id_foreign')
                ->references('id')->on('battlefield_correspondence')
                ->onDelete('cascade');
            $table->foreign('note_id')
                ->references('id')->on('notes')
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
        Schema::dropIfExists('battlefield_correspondence_note');
    }
}
