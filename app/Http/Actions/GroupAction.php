<?php

namespace App\Http\Actions;

use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupAction extends Controller
{
    // !!!! TO CHECK IF IS ADMIN/HELPER FOR $groupId
    public function __invoke(Request $request, int $groupId)
    {
        $userGroups = $request->user()->groups->toArray();
        // $userId = $request->user()->id;
        $userGroupIds = array_column($userGroups, 'id');

        // or validation
        if (!isset($groupId)) {
            return $this->badRequestError();
        }

        if ((isset($groupId) && !in_array($groupId, $userGroupIds))) {
            return response()->json(
                ['errors' => [ 'general' => 'not authorized' ]],
                401
            );
        }

        $group = Group::where('id', $groupId)->get();

        if (!$group->isEmpty()) {
            return response()->json([
                'success' => true,
                'group' => $group->first(),
            ]);
        }

        return $this->badRequestError();

    }

    private function badRequestError(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            ['errors' => ['general' => 'bad request']],
            400
        );
    }
}
