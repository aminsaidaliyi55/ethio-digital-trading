<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueTinIndexFromShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropUnique(['TIN']); // Ensure you have the correct index name here
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->unique('TIN'); // Re-add the unique constraint if needed
        });
    }
}
