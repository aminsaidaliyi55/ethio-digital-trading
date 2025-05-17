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
        
        
$table->uuid('region_id')->nullable(); // UUID for product_id

$table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['region_id']);
        $table->dropColumn('region_id');
    });
}
};
