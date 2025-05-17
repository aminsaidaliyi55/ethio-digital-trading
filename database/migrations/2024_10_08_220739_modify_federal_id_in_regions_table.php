<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFederalIdInRegionsTable extends Migration
{
    public function up()
    {
        Schema::table('regions', function (Blueprint $table) {
            // Changing federal_id to UUID type
            $table->uuid('federal_id')->nullable()->change(); // Make it nullable if you need
            
            // Drop existing foreign key constraint if any before adding a new one
            $table->dropForeign(['federal_id']);
            
            // Re-add the foreign key constraint
            $table->foreign('federal_id')->references('id')->on('federals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('regions', function (Blueprint $table) {
            // Change federal_id back to unsignedBigInteger
            
            // Drop foreign key constraint
            $table->dropForeign(['federal_id']);
        });
    }
}
