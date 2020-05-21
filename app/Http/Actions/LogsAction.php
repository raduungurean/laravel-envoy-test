<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;

class LogsAction extends Controller
{
    public function __invoke(Request $request)
    {
//        echo File::get(storage_path('logs/boa.log'));
//        die;
    }
}
