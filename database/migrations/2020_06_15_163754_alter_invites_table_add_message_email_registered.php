<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvitesTableAddMessageEmailRegistered extends Migration
{
    public function up()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->string('message');
            $table->set('email_already_in', ['yes', 'no'])->default('no')->index();
        });
    }

    public function down()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('message');
            $table->dropColumn('email_already_in');
        });
    }
}
