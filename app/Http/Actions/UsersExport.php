<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class UsersExport extends Controller
{
    public function __invoke(Request $request)
    {
        $users = DB::select('select * from users');
        return $users;
    }
}
