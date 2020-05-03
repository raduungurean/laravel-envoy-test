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
        $userGroups = $request->user()->groups->toArray();
        $allowedGroups = [];
        foreach ($userGroups as $userGroup) {
            $allowedGroups[] = $userGroup['id'];
        }

        if ($groupId) {
            if (!in_array($groupId, $allowedGroups)) {
                // not allowed
                $allowedGroups = [-1];
            }
        }

        $paginator = DB::table('users')
            ->select('*', DB::raw('CAST(stats->>\'$.total\' as UNSIGNED) as total'))
            ->join('user_group', 'user_group.user_id', '=', 'users.id')
            ->whereIn('user_group.group_id', $allowedGroups)
            ->orderBy('total', 'desc')
            ->paginate();

        $paginator->getCollection()->transform(function ($value) {
            $value->stats = json_decode($value->stats);
            return $value;
        });

        return $paginator;
    }
}
