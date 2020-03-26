<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivatedToHashesTable extends Migration
{
    public function up()
    {
        Schema::table('hashes', function (Blueprint $table) {
            $table->smallInteger('activated')->default(0)->index();
        });
    }

    public function down()
    {
        Schema::table('hashes', function (Blueprint $table) {
            $table->dropColumn('activated');
        });
    }
}
