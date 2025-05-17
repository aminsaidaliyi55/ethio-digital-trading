<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new fields for tax and compliance
            $table->decimal('vat', 10, 2)->nullable()->after('barcode'); // VAT rate
            $table->decimal('sales_tax', 10, 2)->nullable()->after('vat'); // Sales tax rate
            $table->string('tax_class')->nullable()->after('sales_tax'); // Tax class
            $table->boolean('tax_exempt')->default(false)->after('tax_class'); // Tax exempt status
            $table->string('tax_id')->nullable()->after('tax_exempt'); // Tax ID
            $table->decimal('base_price', 10, 2)->nullable()->after('tax_id'); // Base price
            $table->decimal('discount_rate', 10, 2)->nullable()->after('base_price'); // Discount rate
            $table->decimal('final_price', 10, 2)->nullable()->after('discount_rate'); // Final price
            $table->string('currency')->default('USD')->after('final_price'); // Currency
            $table->boolean('regulatory_approval')->default(false)->after('currency'); // Regulatory approval
            $table->date('expiration_date')->nullable()->after('regulatory_approval'); // Expiration date
            $table->string('certification_details')->nullable()->after('expiration_date'); // Certification details
            $table->decimal('shipping_weight', 10, 2)->nullable()->after('certification_details'); // Shipping weight
            $table->string('warehouse_location')->nullable()->after('shipping_weight'); // Warehouse location
            $table->string('product_status')->default('active')->after('warehouse_location'); // Product status
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Dropping the new attributes if rolling back the migration
            $table->dropColumn([
                'vat', 
                'sales_tax', 
                'tax_class', 
                'tax_exempt', 
                'tax_id', 
                'base_price', 
                'discount_rate', 
                'final_price', 
                'currency', 
                'regulatory_approval', 
                'expiration_date', 
                'certification_details', 
                'shipping_weight', 
                'warehouse_location', 
                'product_status'
            ]);
        });
    }
}
