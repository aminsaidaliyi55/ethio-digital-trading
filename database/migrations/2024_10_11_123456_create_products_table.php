<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as the primary key
            $table->uuid('shop_id'); // UUID for shop relation
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Optional product description
            $table->decimal('purchased_price', 10, 2); // Purchased price
            $table->decimal('selling_price', 10, 2); // Selling price
            $table->decimal('profit', 10, 2)->nullable(); // Profit (optional)
            $table->decimal('profit_percent', 5, 2)->nullable(); // Profit percentage (optional)
            $table->integer('stock_quantity'); // Stock quantity
            $table->string('sku')->unique(); // SKU (Stock Keeping Unit)
            $table->uuid('category_id'); // UUID for category relation
            $table->string('image')->nullable(); // Product image (optional)
            $table->enum('status', ['active', 'inactive']); // Product status
            $table->integer('stock_in')->default(0);
    $table->integer('stock_out')->default(0);
    $table->decimal('free_per_kg_price', 10, 2)->default(0);
            $table->timestamps(); // Laravel's created_at and updated_at timestamps

            // Foreign key constraints
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade'); // Relationship to shops table
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); // Relationship to categories table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
