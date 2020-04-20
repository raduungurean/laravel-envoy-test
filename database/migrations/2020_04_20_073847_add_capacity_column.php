<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityColumn extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->smallInteger('capacity');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
}
