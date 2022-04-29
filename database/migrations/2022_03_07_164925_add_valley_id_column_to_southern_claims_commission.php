<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValleyIdColumnToSouthernClaimsCommission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('southern_claims_commission', function (Blueprint $table) {
            $table->string('valley_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('southern_claims_commission', function (Blueprint $table) {
            $table->dropColumn('valley_id');
        });
    }
}
