<?php

namespace App\Http\Actions;

use App\Repositories\UserRepository;
use App\User;
use Validator;
use Auth;
use Image;
use Storage;
use Illuminate\Http\Request;

class ProfilePictureAction
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        $extension = $request->file('photo')->extension();
        $fileName = str_random() . '.' . $extension;

        $failed = false;
        $path = '/app/photos/' . $userId . '/' . $fileName;

        try {

            $toPathForStorage = '/photos/' . $userId . '/';
            if (!Storage::exists($toPathForStorage)){
                Storage::makeDirectory($toPathForStorage);
            }

            Image::make($request->file('photo'))
                ->fit(800, 800)
                ->save(storage_path() . $path);

            $toPath = storage_path() . '/app/public/photos/' . $userId . '/';

            $toPathForStorage = '/public/photos/' . $userId . '/';
            if (!Storage::exists($toPathForStorage)){
                Storage::makeDirectory($toPathForStorage);
            }

            Image::make(storage_path() . $path)
                ->fit(50, 50)
                ->save($toPath . $fileName);
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
            return $this->responseFailed($exception->getMessage());
        }

        $user = User::find($userId);

        if ($user) {
            $userArr = $this->userRepository->transformUser($user);
        }

        if (!isset($userArr)) {
            return $this->responseFailed('');
        }

        return response()->json([
            'success' => true,
            'userId' => $userId,
            'path' => $path,
            'toPath' => $toPath . $fileName,
            'user' => $userArr,
            'message' => 'Successfully Updated.'
        ]);
    }

    private function responseFailed($message)
    {
        return response()->json(
            ['errors' => ['general' => 'error uploading profile picture' . $message]],
            400
        );
    }
}
