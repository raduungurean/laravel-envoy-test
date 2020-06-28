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
    private $firebaseAuth;

    public function __construct(
        UserRepository $userRepository,
        \Kreait\Firebase\Auth $firebaseAuth
    ) {
        $this->userRepository = $userRepository;
        $this->firebaseAuth = $firebaseAuth;
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

        $email = $request->input('email');
        $password = $request->input('password');

        if ($user) {
            $userArr = $user->toArray();
            $groups = $this->userRepository->getGroups($user->id);
            $pendingInvites = $this->userRepository->getPendingInvites($userArr['email']);
            $userArr['groups'] = $groups;
            $userArr['pendingInvites'] = $pendingInvites;
        }

        if (isset($userArr)) {

            // $signInResult = $this->firebaseAuth->signInWithEmailAndPassword($email, $password);

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


