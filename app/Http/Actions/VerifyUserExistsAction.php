<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Mail;
use JWTAuth;

class VerifyUserExistsAction extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = auth()->user()->id;

        $user = User::with('groups')
            ->where('id', $userId)
            ->first();

        if ($user) {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'account_does_not_exist',
        ]);
    }
}
