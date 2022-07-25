<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSoldierDossierImagesToManyToManyRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soldier_dossiers', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });

        Schema::create('soldier_dossier_image', function (Blueprint $table) {
            $table->bigInteger('soldier_dossier_id')->unsigned();
            $table->bigInteger('image_id')->unsigned();
            $table->integer('weight')->unsigned();

            $table->foreign('soldier_dossier_id')
                ->references('id')->on('soldier_dossiers')
                ->onDelete('cascade');
            $table->foreign('image_id')
                ->references('id')->on('images')
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
        Schema::dropIfExists('soldier_dossier_image');

        Schema::table('soldier_dossiers', function (Blueprint $table) {
            $table->bigInteger('image_id')->unsigned()->nullable()->after('physical_description');

            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });
    }
}
