<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();;
            $table->string('street')->nullable();;
            $table->string('city')->nullable();;
            $table->string('state')->nullable();;
            $table->string('postcode')->nullable();;
            $table->integer('country_id')->index();;
            $table->string('note')->nullable();;
            $table->string('website')->nullable();;
            $table->string('facebook')->nullable();;
            $table->decimal('latitude', 11, 8)->nullable();;
            $table->decimal('longitude', 11, 8)->nullable();;
            $table->integer('created_by')->index();;
            $table->integer('group_id')->nullable()->index();;
            $table->timestamps();
        });

        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('duration')->default(60);
            $table->timestamp('date');
            $table->smallInteger('confirmations_locked')->default(0);
            $table->integer('created_by')->index();
            $table->smallInteger('team_red_score')->nullable();
            $table->smallInteger('team_blue_score')->nullable();
            $table->integer('location_id')->index();
            $table->integer('group_id')->index();
            $table->set('status', ['played', 'to-play', 'canceled'])
                ->default('to-play')
                ->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('matches');
    }
}
