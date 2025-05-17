<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new fields after 'status'
            $table->integer('minimum_stock_level')->nullable()->after('expiry_date');
            $table->integer('maximum_stock_level')->nullable()->after('minimum_stock_level');
            $table->integer('reorder_level')->nullable()->after('maximum_stock_level');
            $table->string('unit_of_measure')->nullable()->after('reorder_level');
            $table->decimal('discount_price', 10, 2)->nullable()->after('unit_of_measure');
            $table->decimal('weight', 10, 2)->nullable()->after('discount_price');
            $table->decimal('length', 10, 2)->nullable()->after('weight');
            $table->decimal('width', 10, 2)->nullable()->after('length');
            $table->decimal('height', 10, 2)->nullable()->after('width');
            $table->string('barcode')->nullable()->after('height');

            // Foreign key constraint
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'minimum_stock_level',
                'maximum_stock_level', 'reorder_level', 'unit_of_measure', 'discount_price',
                'weight', 'length', 'width', 'height', 'barcode'
            ]);
        });
    }
}
