<?php

namespace App\Http\Actions;

use App\Hash;
use App\Http\Controllers\Controller;
use App\Mail\SendPasswordRecoveryLink;
use Illuminate\Http\Request;
use App\User;
use Mail;
use Validator;

class PasswordRecoveryLinkAction extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $hash = new Hash();
            $hash->player_id = $user->id;
            $hash->hash_type = 'forgot-password';
            $hash->hash = hash_hmac('sha256', str_random(40), config('app.key'));;
            $hash->save();

            if ($hash->id) {
                Mail::to($user)
                    ->send(new SendPasswordRecoveryLink($user, $hash->hash));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully sent.'
        ], 200);
    }
}
