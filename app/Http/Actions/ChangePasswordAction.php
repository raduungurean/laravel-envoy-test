<?php

namespace App\Http\Actions;

use App\User;
use Exception;
use Validator;
use Auth;
use Illuminate\Http\Request;

class ChangePasswordAction
{
    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        $failed = false;

        try {
            User::where('id', $userId)
                ->update([
                    'password' => bcrypt($request->password),
                ]);
        } catch (Exception $exception) {
            $failed = true;
        }

        if ($failed) {
            return response()->json(
                ['errors' => [ 'general' => 'error updating the password' ]],
                400
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated.'
        ]);
    }
}
