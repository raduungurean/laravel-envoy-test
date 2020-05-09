<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Storage;

class PlayerPictureAction extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $userArray = $user->toArray();
        $photo = $userArray['photo'];
        $file = 'photos' . DIRECTORY_SEPARATOR .$userArray['id'] . DIRECTORY_SEPARATOR . ($photo);

        if (!Storage::disk('local')->exists($file)) {
            $defaultFile = 'public' . DIRECTORY_SEPARATOR . 'player.jpg';
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $defaultFile);
        } else {
            $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file);
        }

        return response()->file($filePath);
    }
}
