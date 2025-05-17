<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminIdToFederalsRegionsZonesWoredasKebeles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Add admin_id to federals table
    Schema::table('federals', function (Blueprint $table) {
        $table->uuid('admin_id')->nullable()->after('name'); // Change admin_id to UUID
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null'); // Reference UUID
    });

    // Add admin_id to regions table
    Schema::table('regions', function (Blueprint $table) {
        $table->uuid('admin_id')->nullable()->after('name'); // Change admin_id to UUID
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null'); // Reference UUID
    });

    // Add admin_id to zones table
    Schema::table('zones', function (Blueprint $table) {
        $table->uuid('admin_id')->nullable()->after('name'); // Change admin_id to UUID
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null'); // Reference UUID
    });

    // Add admin_id to woredas table
    Schema::table('woredas', function (Blueprint $table) {
        $table->uuid('admin_id')->nullable()->after('name'); // Change admin_id to UUID
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null'); // Reference UUID
    });

    // Add admin_id to kebeles table
    Schema::table('kebeles', function (Blueprint $table) {
        $table->uuid('admin_id')->nullable()->after('name'); // Change admin_id to UUID
        $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null'); // Reference UUID
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop admin_id from federals table
        Schema::table('federals', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });

        // Drop admin_id from regions table
        Schema::table('regions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });

        // Drop admin_id from zones table
        Schema::table('zones', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });

        // Drop admin_id from woredas table
        Schema::table('woredas', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });

        // Drop admin_id from kebeles table
        Schema::table('kebeles', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
}
