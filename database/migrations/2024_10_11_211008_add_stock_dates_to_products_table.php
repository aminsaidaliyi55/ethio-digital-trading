<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockDatesToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('stock_in_date')->nullable(); // Adding stock in date
            $table->date('stock_out_date')->nullable(); // Adding stock out date
            $table->integer('stock_in_years')->nullable(); // To store the difference in years
            $table->integer('stock_in_months')->nullable(); // To store the difference in months
            $table->integer('stock_in_days')->nullable(); // To store the difference in days
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_in_date', 'stock_out_date', 'stock_in_years', 'stock_in_months', 'stock_in_days']);
        });
    }
}
