<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

    Schema::create('woredas', function (Blueprint $table) {
$table->uuid('id')->primary();
       $table->uuid('zone_id'); // UUID for product_id
    $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
    $table->string('name');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woredas');
    }
};
