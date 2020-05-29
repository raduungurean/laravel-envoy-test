<?php

namespace App\Http\Actions;

use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteGroupAction extends Controller
{
    // !!!! TO CHECK IF IS ADMIN FOR $request->id GROUP TOO
    public function __invoke(Request $request)
    {
        $userGroups = $request->user()->groups->toArray();
        $userGroupIds = array_column($userGroups, 'id');

        // or validation
        if (!isset($request->id)) {
            return $this->badRequestError();
        }

        if ((isset($request->id) && !in_array($request->id, $userGroupIds))) {
            return response()->json(
                ['errors' => [ 'general' => 'not authorized' ]],
                401
            );
        }

        $deleted = Group::where('id', $request->id)->delete();

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
