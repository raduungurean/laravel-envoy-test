<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use DB;
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
            } else {
                $allowedGroups = [$groupId];
            }
        }

        $paginator = DB::table('users')
            ->select('*')
            ->join('user_group', 'user_group.user_id', '=', 'users.id')
            ->whereIn('user_group.group_id', $allowedGroups)
            ->whereNull('deleted_at')
            ->paginate();

        $paginator->getCollection()->transform(function ($value) {
            $value->thumb = config('app.url') . 'storage' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . $value->user_id . DIRECTORY_SEPARATOR . $value->photo;
            $value->stats = json_decode($value->stats);
            unset($value->email,
                $value->email_verified_at,
                $value->photo,
                $value->password,
                $value->remember_token,
                $value->deleted_at,
                $value->notifications,
                $value->user_id,
                $value->group_id);
            return $value;
        });

        return $paginator;
    }
}
