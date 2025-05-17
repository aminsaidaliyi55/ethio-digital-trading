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
    Schema::table('products', function (Blueprint $table) {
        $table->decimal('tax', 8, 2)->after('vat')->nullable();
        $table->decimal('total_price', 10, 2)->after('tax')->nullable();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('tax');
        $table->dropColumn('total_price');
    });
}

};
