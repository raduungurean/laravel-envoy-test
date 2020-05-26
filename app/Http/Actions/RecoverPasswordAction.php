<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Hash;
use Mail;
use Validator;

class RecoverPasswordAction extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'hash' => 'required',
            'password' => 'required|confirmed|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        $hash = $request->input('hash');
        $id = $request->input('id');
        $password = $request->input('password');
        $passwordConfirmation = $request->input('password_confirmation');

        $checkHash = Hash::where('player_id', $id)
            ->where('hash', $hash)
            ->where('hash_type', 'forgot-password')
            ->first();

        if ($checkHash && $password === $passwordConfirmation) {
            $user = User::find($id);
            if ($user) {
                $user->password = bcrypt($password);
                $user->save();

                $checkHash->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Your password has been updated.',
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry! There was an error',
        ]);
    }
}
