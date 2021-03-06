<?php

namespace App\Http\Actions;

use App\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

class RecoverPasswordFormAction extends Controller
{
    public function __invoke(Request $request, string $id, string $hash)
    {
        $checkHash = Hash::where('player_id', $id)
            ->where('hash', $hash)
            ->where('hash_type', 'forgot-password')
            ->first();

        if ($checkHash) {
            return view('auth-flow.reset-password-form')->with([
                'id' => $id,
                'hash' => $hash,
            ]);
        }

        return view('auth-flow.password-error');
    }
}
