<?php

namespace App\Http\Actions;

use App\Hash;
use App\Http\Controllers\Controller;
use App\Mail\RegisteredPleaseActivate;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Mail;

class RegisterAction extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $playerId = $user->id;

        if ($playerId) {
            $hash = new Hash();
            $hash->player_id = $playerId;
            $hash->hash = hash_hmac('sha256', str_random(40), config('app.key'));;
            $hash->save();

            if ($hash->id) {
                Mail::to($user)
                    ->send(new RegisteredPleaseActivate($user, $hash->hash));
            }
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
}
