<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mockery\Exception;
use Socialite;
use Mail;

class ValidateAccessTokenAction extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = Socialite::driver($request->provider)
                ->stateless()
                ->userFromToken($request->accessToken);
        } catch (\GuzzleHttp\Exception\ClientException $clientException) {
            return response()->json([
                'success' => false,
                'message' => 'Error authenticating. Please try again latter',
            ], 400);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error authenticating. Please try again latter',
            ], 400);
        }

        return response()->json($user);
    }
}
