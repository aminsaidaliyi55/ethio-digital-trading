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
        
     $table->uuid('zone_id')->nullable(); // UUID for product_id

    $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['zone_id']);
        $table->dropColumn('zone_id');
    });
}
};
