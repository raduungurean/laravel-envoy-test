<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\InviteRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Auth;

class InviteRejectingAction extends Controller
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

            $invite->delete();

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
