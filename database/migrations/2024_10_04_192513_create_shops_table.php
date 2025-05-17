<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key as UUID
            $table->uuid('owner_id'); // UUID for owner_id
            $table->string('name');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone');
            $table->string('website')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->text('category_id')->nullable();
            $table->string('opening_hours');
            $table->string('shop_license')->nullable(); // Store file path
            $table->integer('TIN')->unique(); // Add unique constraint on TIN
            $table->decimal('total_capital', 15, 2); // Add the total_capital field

            $table->timestamps();
        $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');

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
            $table->dropForeign(['owner_id']); // Drop foreign key for owner_id
            $table->dropUnique(['TIN']); // Drop unique constraint on TIN
        });

        Schema::dropIfExists('shops');
    }
}
