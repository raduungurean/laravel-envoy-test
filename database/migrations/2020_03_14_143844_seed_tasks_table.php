<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedTasksTable extends Migration
{
    public function up()
    {
        Artisan::call('db:seed', [
            '--class' => TasksTableSeeder::class,
            '--force' => true,
        ]);
    }

    public function down()
    {

    }
}
