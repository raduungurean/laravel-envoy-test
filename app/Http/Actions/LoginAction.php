<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;

class LoginAction extends Controller
{
    public function __invoke(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        $userId = auth()->user()->id;
        $user = User::with('groups')->find($userId);

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
        ]);
    }
}
