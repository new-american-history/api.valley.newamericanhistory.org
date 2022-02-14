<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->text('epigraph')->nullable()->after('location');
            $table->text('valley_notes')->nullable()->after('postscript');
            $table->text('headline')->nullable()->change();
            $table->text('closing_salutation')->nullable()->change();
            $table->text('signed')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->string('signed')->nullable()->change();
            $table->string('closing_salutation')->nullable()->change();
            $table->string('headline')->nullable()->change();
            $table->dropColumn('valley_notes');
            $table->dropColumn('epigraph');
        });
    }
}
