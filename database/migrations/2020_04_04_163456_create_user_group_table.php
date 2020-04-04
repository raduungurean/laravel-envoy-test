<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupTable extends Migration
{
    public function up()
    {
        Schema::create('user_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('group_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_group');
    }
}
