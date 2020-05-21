<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;

class LoginAction extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return $this->responseError();
        }

        $userId = auth()->user()->id;

        $user = User::find($userId);

        if ($user) {
            $userArr = $user->toArray();
            $groups = $this->userRepository->getGroups($user->id);
            $userArr['groups'] = $groups;
        }

        if (isset($userArr)) {
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $userArr,
            ]);
        }

        return $this->responseError();
    }

    private function responseError(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Invalid Email or Password',
        ], 401);
    }
}


