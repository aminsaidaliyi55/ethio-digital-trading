<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationsNotifiableIdColumn extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->uuid('notifiable_id')->change();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('notifiable_id')->change();
        });
    }
}
