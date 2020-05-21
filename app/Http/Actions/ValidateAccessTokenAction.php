<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Mockery\Exception;
use Socialite;
use JWTAuth;

class ValidateAccessTokenAction extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

            $userArr = $user->toArray();
            $groups = $this->userRepository->getGroups($user->id);
            $userArr['groups'] = $groups;

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $userArr,
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
