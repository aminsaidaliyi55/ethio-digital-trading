<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKebeleIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add kebele_id as UUID
            $table->uuid('kebele_id')->nullable(); // UUID for kebele_id, nullable if not required for all users

            // Set foreign key constraint for kebele_id referencing kebeles table
            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint and kebele_id column
            $table->dropForeign(['kebele_id']);
            $table->dropColumn('kebele_id');
        });
    }
}
