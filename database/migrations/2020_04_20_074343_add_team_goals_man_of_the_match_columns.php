<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamGoalsManOfTheMatchColumns extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->smallInteger('team_goals')->default(0);
            $table->smallInteger('man_of_the_match')->index()->default(0);
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('team_goals');
            $table->dropColumn('man_of_the_match');
        });
    }
}
