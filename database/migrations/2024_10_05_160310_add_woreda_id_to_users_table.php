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
        Schema::table('users', function (Blueprint $table) {
            // Add woreda_id as a UUID
            $table->uuid('woreda_id')->nullable(); // Add nullable if the column is optional

            // Set foreign key constraint for woreda_id referencing the woredas table
            $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraint and the woreda_id column
            $table->dropForeign(['woreda_id']);
            $table->dropColumn('woreda_id');
        });
    }
};
