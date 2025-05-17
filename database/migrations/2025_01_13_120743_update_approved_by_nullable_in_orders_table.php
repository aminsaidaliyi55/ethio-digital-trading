<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateApprovedByNullableInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('approved_by')->nullable()->change(); // Make the column nullable
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('approved_by')->nullable(false)->change(); // Revert to non-nullable if needed
        });
    }
}
