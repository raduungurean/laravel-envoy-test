<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlayersTableMakeFkeyNullable extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('fkey')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('fkey')->nullable(false)->change();
        });
    }
}
