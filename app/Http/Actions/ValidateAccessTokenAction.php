<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Mockery\Exception;
use Socialite;
use JWTAuth;

class ValidateAccessTokenAction extends Controller
{
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

        $user = User::with('groups')
            ->where('email', $providerUser->email)
            ->first();

        if ($user) {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
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
