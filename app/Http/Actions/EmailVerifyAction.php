<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hash;
use App\User;

class EmailVerifyAction extends Controller
{
    public function __invoke(Request $request, string $id, string $hash)
    {
        $checkHash = Hash::where('player_id', $id)
            ->where('hash', $hash)
            ->where('hash_type', 'verify-account')
            ->first();

        if ($checkHash) {
            $user = User::find($id);
            if ($checkHash->activated === 0 || is_null($checkHash->activated)) {
                $checkHash->activated = 1;
                $checkHash->save();
                return view('auth-flow.activated', [ 'user' => $user ]);
            } else {
                return view('auth-flow.activated-already', ['user' => $user]);
            }
        }
        return view('auth-flow.activation-error');
    }
}
