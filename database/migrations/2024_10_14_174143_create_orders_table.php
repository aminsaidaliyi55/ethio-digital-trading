<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID as primary key
            $table->uuid('user_id'); // Foreign key for customer
            $table->uuid('approved_by')->nullable(); // Foreign key for approver
            $table->uuid('product_id'); // Foreign key for product
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending'); // Order status
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
                });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
