<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupsExport extends Controller
{
    public function __invoke(Request $request)
    {
        $groups = \App\Group::all();

        return $groups;
    }
}
