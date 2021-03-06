<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class PlayerPictureAction extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO to verify by groupId if has access to view
        // if not return empty profile photo
        $user = $request->user();
        $userArray = $user->toArray();
        $photo = $userArray['photo'];
        if (empty($photo)) {
            $photo = 'aaaa.jpg';
        }
        $file = 'photos' . DIRECTORY_SEPARATOR . $userArray['id'] . DIRECTORY_SEPARATOR . ($photo);
        if (!Storage::disk('local')->exists($file)) {
            $defaultFile = 'public' . DIRECTORY_SEPARATOR . 'player.jpg';
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $defaultFile);
        } else {
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file);
        }

        return response()->file($filePath);
    }
}
