<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePlayersTableMakePhotoNullable extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->text('photo')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->text('photo')->nullable()->change();
        });
    }
}
