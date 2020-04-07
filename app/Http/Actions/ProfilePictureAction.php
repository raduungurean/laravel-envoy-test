<?php

namespace App\Http\Actions;

use App\User;
use Validator;
use Auth;
use Image;
use Illuminate\Http\Request;

class ProfilePictureAction
{
    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $extension = $request->file('photo')->extension();
        $fileName = str_random() . '.' . $extension;

        $failed = false;

        try {
            Image::make($request->file('photo'))
                ->fit(800, 800)
                ->save(storage_path() . '/app/photos/' . $userId . '/' . $fileName);
        } catch (\Exception $exception) {
            $failed = true;
        }

        try {
            User::where('id', $userId)
                ->update([
                    'photo' => $fileName,
                ]);
        } catch (\Exception $exception) {
            $failed = true;
        }

        if ($failed) {
            return response()->json(
                ['errors' => ['general' => 'error updating the password']]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated.'
        ]);
    }
}
