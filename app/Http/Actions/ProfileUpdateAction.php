<?php

namespace App\Http\Actions;

use App\Repositories\UserRepository;
use App\User;
use Exception;
use Validator;
use Auth;
use Illuminate\Http\Request;

class ProfileUpdateAction
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
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $userId . '|max:100',
            'username' => 'max:50|unique:users,username,' . $userId,
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        try {
            $user = User::find($userId);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->save();

            $userArr = $user->toArray();
            $groups = $this->userRepository->getGroups($user->id);
            $pendingInvites = $this->userRepository->getPendingInvites($userArr['email']);
            $userArr['groups'] = $groups;
            $userArr['pendingInvites'] = $pendingInvites;

            return response()->json([
                'success' => true,
                'user' => $userArr,
                'message' => 'Successfully Updated.'
            ]);
        } catch (Exception $exception) {
            return response()->json(
                ['errors' => ['general' => 'error updating the profile']],
                400
            );
        }
    }
}
