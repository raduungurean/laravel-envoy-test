<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Image;
use Storage;

class PlayerThumbAction extends Controller
{
    public function __invoke(Request $request)
    {
        $jwt = $request->get('jwt');
        $playerId = $request->get('id');
        try {
            $loggedInInfo = JWTAuth::setToken($jwt)->toUser();
            if ($loggedInInfo->id) {
                $loggedInUserId = $loggedInInfo->id;
                $user = User::find($loggedInUserId);
                if (!$user) {
                    return $this->emptyPicture();
                }
                $player = User::find($playerId);
                if ($player) {
                    $photo = $player->photo;
                    if (empty($photo)) {
                        $photo = 'aaaa.jpg';
                    }
                    $file = 'photos' . DIRECTORY_SEPARATOR . $playerId . DIRECTORY_SEPARATOR . $photo;
                    if (!Storage::disk('local')->exists($file)) {
                        $defaultFile = 'public' . DIRECTORY_SEPARATOR . 'player.jpg';
                        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $defaultFile);
                    } else {
                        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file);
                    }
                    return Image::make($filePath)->response();
                }
                return $this->emptyPicture();
            }
        } catch (\Exception $exception) {
            return $this->emptyPicture();
        }
    }

    private function emptyPicture()
    {
        $defaultFile = 'public' . DIRECTORY_SEPARATOR . 'player.jpg';
        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $defaultFile);
        return Image::make($filePath)->response();
    }
}
