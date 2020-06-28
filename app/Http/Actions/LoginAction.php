<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Kreait\Firebase\Auth as FirebaseAuth;

class LoginAction extends Controller
{
    private $userRepository;
    private $firebaseAuth;

    public function __construct(
        UserRepository $userRepository,
        FirebaseAuth $firebaseAuth
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

        if (!$user) {
            return $this->responseError();
        }

        $userArr = $this->userRepository->transformUser($user);

        if (isset($userArr)) {

            try {
                $customToken = $this->firebaseAuth
                    ->createCustomToken((string)$userId);
            } catch (\Exception $exception) {
                echo($exception->getMessage());
                die();
                return $this->responseError();
            }

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $userArr,
                'id_token' => (string) $customToken,
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
