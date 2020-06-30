<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MatchesExport extends Controller
{
    public function __invoke(Request $request)
    {
        $matches = \App\Match::with('subscriptions')->get();

        return $matches;
    }
}
