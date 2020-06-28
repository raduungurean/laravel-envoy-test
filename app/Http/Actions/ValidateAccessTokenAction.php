<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Mockery\Exception;
use Socialite;
use JWTAuth;

class ValidateAccessTokenAction extends Controller
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
        try {
            $providerUser = Socialite::driver($request->provider)
                ->stateless()
                ->userFromToken($request->accessToken);
        } catch (\GuzzleHttp\Exception\ClientException $clientException) {
            return $this->responseError();
        } catch (Exception $exception) {
            return $this->responseError();
        }

        $user = User::where('email', $providerUser->email)
            ->first();

        if ($user) {

            $userArr = $this->userRepository->transformUser($user);

            $token = JWTAuth::fromUser($user);

            try {
                $customToken = $this->firebaseAuth
                    ->createCustomToken((string)$user->id);
            } catch (\Exception $exception) {
                return $this->responseError();
            }

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $userArr,
                'id_token' => $customToken,
            ]);
        }

        // success true
        return response()->json([
            'success' => true,
            'message' => 'account_does_not_exist',
            'user' => $providerUser,
        ]);
    }

    private function responseError(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Error authenticating. Please try again latter',
        ], 400);
    }
}
