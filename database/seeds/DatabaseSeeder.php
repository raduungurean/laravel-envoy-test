<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\User::query()->truncate();
        \App\Group::query()->truncate();
        DB::table('user_group')->truncate();
        Schema::dropIfExists('tasks');
        $this->call(GroupsSeeder::class);
        $this->call(UserSeeder::class);
    }
}
