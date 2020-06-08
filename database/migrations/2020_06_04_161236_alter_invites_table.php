<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvitesTable extends Migration
{
    public function up()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->integer('group_id')->index();
        });
    }

    public function down()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}
