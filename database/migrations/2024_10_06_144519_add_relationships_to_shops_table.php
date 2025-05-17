<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToShopsTable extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Add UUID fields and foreign key constraints
            $table->uuid('federal_id')->nullable();

            $table->uuid('region_id')->nullable();

            $table->uuid('zone_id')->nullable();

            $table->uuid('woreda_id')->nullable();

            $table->uuid('kebele_id')->nullable();
                        $table->foreign('federal_id')->references('id')->on('federals')->onDelete('cascade');

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');

            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');

            $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');

            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Drop foreign keys first, then the columns
            $table->dropForeign(['federal_id']);
            $table->dropColumn('federal_id');

            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');

            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');

            $table->dropForeign(['woreda_id']);
            $table->dropColumn('woreda_id');

            $table->dropForeign(['kebele_id']);
            $table->dropColumn('kebele_id');
        });
    }
}
