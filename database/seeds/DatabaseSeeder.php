<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\User::query()->truncate();
        \App\Group::query()->truncate();
        \App\Location::query()->truncate();
        \App\Match::query()->truncate();
        \App\Subscription::query()->truncate();
        \App\Role::query()->truncate();
        DB::table('user_group')->truncate();
        DB::table('role_user_group')->truncate();
        $this->call(GroupsSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdditionalRolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(MatchesSeeder::class);
        $this->call(LocationsTableSeeder::class);
    }
}
