<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // Import Str to generate UUIDs

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID as primary key
            $table->string('name'); // Name of the customer
            $table->string('email')->unique(); // Unique email
            $table->string('phone')->nullable(); // Phone number (nullable)
            $table->string('address')->nullable(); // Address (nullable)
            $table->string('city')->nullable(); // City (nullable)
            $table->string('state')->nullable(); // State (nullable)
            $table->string('postal_code')->nullable(); // Postal code (nullable)
            $table->string('country')->nullable(); // Country (nullable)
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
