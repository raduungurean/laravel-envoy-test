<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Mail;
use Socialite;
use Storage;
use JWTAuth;
use Str;

class CreateAccountAction extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $providerUser = Socialite::driver($request->provider)
                ->stateless()
                ->userFromToken($request->accessToken);
        } catch (\GuzzleHttp\Exception\ClientException $clientException) {
            return $this->responseError();
        } catch (\Exception $exception) {
            return $this->responseError();
        }

        $profileImage = $providerUser->avatar;
        $email = $providerUser->user['email'];

        if ($providerUser->user['given_name'] && $providerUser->user['family_name']) {
            $firstName = $providerUser->user['given_name'];
            $lastName = $providerUser->user['family_name'];
        } else if ($providerUser->name) {
            $parts = explode(' ', $providerUser->name);
            $firstName = $parts[0];
            $lastName = $parts[count($parts - 1)];
        }

        $user = User::where('email', $providerUser->email)
            ->first();

        if (!$user) {
            $newUserAccount = new User();
            $newUserAccount->first_name = $firstName;
            $newUserAccount->last_name = $lastName;
            $newUserAccount->email = $email;
            $newUserAccount->password = bcrypt(Str::random(40));
            $newUserAccount->save();

            $newUserAccountId = $newUserAccount->id;

            if ($newUserAccountId) {
                if ($profileImage) {
                    $extension =  getImageExtension($profileImage);
                    $photoName = $newUserAccountId . '-' . sha1(time()) . '.' . $extension;
                    $newUserAccount->photo = $photoName;
                    $newUserAccount->save();
                    $contents = file_get_contents($profileImage);
                    Storage::disk('local')
                        ->put('photos/' . $newUserAccountId . '/' . $photoName, $contents);
                }

                $token = JWTAuth::fromUser($newUserAccount);

                $retUser = $newUserAccount->toArray();
                $retUser['groups'] = [];
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $retUser,
                ]);
            }
        }

        return $this->responseError();
    }

    private function responseError(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Error registering. Please try again latter',
        ], 400);
    }
}

function getImageExtension($image_path)
{
    $mimes  = array(
        IMAGETYPE_GIF => "gif",
        IMAGETYPE_JPEG => "jpg",
        IMAGETYPE_PNG => "png",
    );

    if (($image_type = exif_imagetype($image_path)) && (array_key_exists($image_type ,$mimes))) {
        return $mimes[$image_type];
    } else {
        return 'jpg';
    }
}
