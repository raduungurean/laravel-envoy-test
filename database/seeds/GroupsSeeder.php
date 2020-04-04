<?php

use Illuminate\Database\Seeder;

class GroupsSeeder extends Seeder
{
    public function run()
    {
        $group = new \App\Group();
        $group->name = 'FC Bogdan Radu';
        $group->player_id = 1;
        $group->save();
    }
}
