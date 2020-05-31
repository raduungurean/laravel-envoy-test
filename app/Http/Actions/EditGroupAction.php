<?php

namespace App\Http\Actions;

use App\Group;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Validator;

class EditGroupAction extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // !!!! TO CHECK IF IS ADMIN/HELPER FOR $groupId
    public function __invoke(Request $request, int $groupId)
    {
        $userGroups = $request->user()->groups->toArray();
        $userId = $request->user()->id;
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

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|max:100|unique:groups,name,' . $groupId,
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        $groupS = Group::where('id', $groupId)->get();

        try {
            if ($group = $groupS->first()) {
                $group->name = $request->input('group_name');
                $group->short_description = $request->input('short_description');
                $group->save();
            }
        } catch (\Exception $exception) {
            $this->badRequestError();
        }

        $groups = $this->userRepository->getGroups($userId);

        return response()->json([
            'success' => true,
            'groups' => $groups,
            'message' => 'Successfully updated.',
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
