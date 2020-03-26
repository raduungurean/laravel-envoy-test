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
            if ($checkHash->activted === 0) {
                $checkHash->activated = 1;
                $checkHash->save();
                return view('user-messages.activated', [ 'user' => $user ]);
            } else {
                return view('user-messages.activated-already', ['user' => $user]);
            }
        }
        return view('user-messages.activation-error');
    }
}
