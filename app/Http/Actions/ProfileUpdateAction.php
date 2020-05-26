<?php

namespace App\Http\Actions;

use App\User;
use Exception;
use Validator;
use Auth;
use Illuminate\Http\Request;

class ProfileUpdateAction
{
    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $userId . '|max:100',
            'username' => 'max:50|unique:users,username,' . $userId,
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
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'username' => $request->username,
                ]);
        } catch (Exception $exception) {
            $failed = true;
        }

        if ($failed) {
            return response()->json(
                ['errors' => ['general' => 'error updating the profile']],
                400
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated.'
        ]);
    }
}
