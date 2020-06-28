<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolesExport extends Controller
{
    public function __invoke(Request $request)
    {
        $roles = \App\Role::all();

        return $roles;
    }
}
