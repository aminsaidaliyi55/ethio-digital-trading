<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
         public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->uuid('federal_id')->nullable(); // UUID for product_id
          $table->foreign('federal_id')->references('id')->on('federals')->onDelete('cascade');
        });




}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['federal_id']);
        $table->dropColumn('federal_id');
    });
}
};
