<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMatchesTableAddSubscriptionsColumn extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->json('subscriptions')->nullable();
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('subscriptions');
        });
    }
}
