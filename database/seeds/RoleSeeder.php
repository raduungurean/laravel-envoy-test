<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'SuperAdmin', 'code' => 'super-admin'],
            ['name' => 'GroupAdmin', 'code' => 'group-admin'],
            ['name' => 'Helper', 'code' => 'helper'],
            ['name' => 'Player', 'code' => 'player'],
        ];

        foreach ($roles as $role) {
            $r = new \App\Role();
            $r->name = $role['name'];
            $r->code = $role['code'];
            $r->save();
        }
    }
}
