<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserGroup extends Migration
{
    public function up()
    {
        Schema::create('role_user_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('role_id');
            $table->bigInteger('group_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user_group');
    }
}
