<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableName extends Migration
{
    public function up()
    {
        Schema::rename('users', 'players');
    }

    public function down()
    {
        Schema::rename('players', 'users');
    }
}
