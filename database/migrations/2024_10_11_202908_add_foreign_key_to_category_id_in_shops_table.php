<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToCategoryIdInShopsTable extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('set null'); // Adjust delete action as necessary
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['category_id']);
        });
    }
}
