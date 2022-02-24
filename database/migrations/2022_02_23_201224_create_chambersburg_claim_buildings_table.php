<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChambersburgClaimBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chambersburg_claim_buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('building_number')->unsigned()->nullable();
            $table->bigInteger('possible_claim_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('image_heading')->nullable();
            $table->string('image_title')->nullable();
            $table->bigInteger('image_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('possible_claim_id')
                ->references('id')->on('chambersburg_claims')
                ->onDelete('set null');
            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });

        Schema::table('chambersburg_claims', function (Blueprint $table) {
            $table->dropForeign(['image_id']);

            $table->dropColumn('building_number');
            $table->dropColumn('description');
            $table->dropColumn('image_heading');
            $table->dropColumn('image_title');
            $table->dropColumn('image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chambersburg_claim_buildings');

        Schema::table('chambersburg_claims', function (Blueprint $table) {
            $table->string('image_heading')->nullable()->after('notes');
            $table->string('image_title')->nullable()->after('notes');
            $table->bigInteger('image_id')->unsigned()->nullable()->after('notes');
            $table->text('description')->nullable()->after('notes');
            $table->string('building_number')->nullable()->after('notes');

            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });
    }
}
