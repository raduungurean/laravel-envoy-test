<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePlayers extends Migration
{
    public function up()
    {
        Schema::rename('players', 'users');
    }

    public function down()
    {
        Schema::rename('users', 'players');
    }
}
