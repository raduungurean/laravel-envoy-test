<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHashesTable extends Migration
{
    public function up()
    {
        Schema::create('hashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('player_id')
                ->index();
            $table->set('hash_type', ['forgot-password', 'verify-account'])
                ->default('verify-account')
                ->index();
            $table->text('hash');
            $table->timestamps();
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('fkey');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hashes');
        Schema::table('players', function (Blueprint $table) {
            $table->string('fkey', 255)->nullable()->index();
        });
    }
}
