<?php

namespace App\Http\Actions;

use App\Group;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DB;

class AddGroupAction extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|max:100|unique:groups,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        // TODO, to refactor this
        // repository
        $g = new Group();
        $g->name = $request->group_name;
        $g->player_id = $userId;
        if ($g->save()) {
            $groupId = $g->id;
            DB::table('role_user_group')->insert(
                ['user_id' => $userId, 'role_id' => 2, 'group_id' => $groupId]
            );
            DB::table('user_group')->insert(
                ['user_id' => $userId, 'group_id' => $groupId, ]
            );
        }

        $groups = $this->userRepository->getGroups($userId);

        return response()->json([
            'success' => true,
            'groups' => $groups,
            'message' => 'Successfully created.',
        ]);
    }
}
