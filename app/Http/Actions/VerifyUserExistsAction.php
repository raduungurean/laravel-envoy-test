<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class VerifyUserExistsAction extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = auth()->user()->id;

        $user = User::with('groups')
            ->where('id', $userId)
            ->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'account_does_not_exist',
        ]);
    }
}
