<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class UsersExport extends Controller
{
    public function __invoke(Request $request)
    {
        $appUsers = \App\User::with('groups')
            ->with('roles')
            ->get();
        return $appUsers->makeVisible(['password']);
//        $users = DB::select('select * from users');
//        return collect($users)->map(function ($item) {
//            $item->stats = json_decode($item->stats);
//            return $item;
//        });
    }
}
