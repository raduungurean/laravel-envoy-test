<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\InviteRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use DB;
use Auth;

class InviteAcceptingAction extends Controller
{
    private $userRepository;
    private $inviteRepository;

    public function __construct(
        UserRepository $userRepository,
        InviteRepository $inviteRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->inviteRepository = $inviteRepository;
    }

    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;
        $hash = $request->hash;
        try {
            $user = User::find($userId);
            $invite = $this->inviteRepository->getByHashAndEmail($hash, $user->email);
            if (!$invite) {
                return $this->responseError();
            }

            DB::table('role_user_group')->insert(
                ['user_id' => $userId, 'role_id' => 2, 'group_id' => $invite->group_id]
            );
            DB::table('user_group')->insert(
                ['user_id' => $userId, 'group_id' => $invite->group_id, ]
            );

            $invite->accepted = 'yes';
            $invite->save();

            $userArr = $user->toArray();
            $groups = $this->userRepository->getGroups($user->id);
            $pendingInvites = $this->userRepository->getPendingInvites($userArr['email']);
            $userArr['groups'] = $groups;
            $userArr['pendingInvites'] = $pendingInvites;

            return response()->json([
                'success' => true,
                'user' => $userArr,
            ]);

        } catch (\Exception $e) {
            print_r($e->getMessage());
            die;
            return $this->responseError();
        }
    }

    private function responseError(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Error',
        ], 400);
    }
}
