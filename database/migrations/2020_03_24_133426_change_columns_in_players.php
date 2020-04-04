<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInPlayers extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->string('last_name', 100)->index();
            $table->string('fkey', 255)->index();
            $table->text('photo');
            $table->json('notifications');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name');
            $table->dropSoftDeletes();
        });
    }
}
