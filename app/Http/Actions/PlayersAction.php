<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use DB;
use Cache;
use Illuminate\Http\Request;

class PlayersAction extends Controller
{
    public function __invoke(Request $request, int $groupId = null)
    {
        // filter by logged in user-groups maybe in middleware
        $allowedGroups = [1];

        $page = $request->input('page');

        $paginator = DB::table('players')
            ->select('*', DB::raw('CAST(stats->>\'$.total\' as UNSIGNED) as total'))
            ->orderBy('total', 'desc')
            ->paginate();

        $paginator->getCollection()->transform(function ($value) {
            $value->stats = json_decode($value->stats);
            return $value;
        });

        return $paginator;
    }
}
