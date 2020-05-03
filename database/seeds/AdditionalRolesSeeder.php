<?php

use Illuminate\Database\Seeder;

class AdditionalRolesSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_user_group')->insert(
            ['user_id' => 1, 'role_id' => 1]
        );
        DB::table('role_user_group')->insert(
            ['user_id' => 1, 'group_id' => 1, 'role_id' => 2]
        );
        DB::table('role_user_group')->insert(
            ['user_id' => 2, 'group_id' => 1, 'role_id' => 2]
        );
    }
}
