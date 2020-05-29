<?php

namespace App\Http\Actions;

use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteGroupAction extends Controller
{
    // !!!! TO CHECK IF IS ADMIN FOR $request->id GROUP TOO
    public function __invoke(Request $request, int $groupId)
    {
        $userGroups = $request->user()->groups->toArray();
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

        $deleted = Group::where('id', $groupId)->delete();

        if (!$deleted) {
            return $this->badRequestError();
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Deleted.'
        ]);
    }

    private function badRequestError(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            ['errors' => ['general' => 'bad request']],
            400
        );
    }
}
