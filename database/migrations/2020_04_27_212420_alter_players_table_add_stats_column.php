<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlayersTableAddStatsColumn extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->json('stats')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('stats');
        });
    }
}
