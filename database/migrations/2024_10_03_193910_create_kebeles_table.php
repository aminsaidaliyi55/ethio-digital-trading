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
    Schema::create('kebeles', function (Blueprint $table) {
$table->uuid('id')->primary();
  $table->uuid('woreda_id'); // UUID for product_id
    $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');
        $table->string('name');
        $table->timestamps();

        // Define the foreign key constraint with proper data type
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebeles');
    }
};
