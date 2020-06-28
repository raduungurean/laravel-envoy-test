<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Socialite;
use Storage;
use JWTAuth;
use Str;

class CreateAccountFromProviderAction extends Controller
{
    private $userRepository;
    private $firebaseAuth;

    public function __construct(
        UserRepository $userRepository,
        FirebaseAuth $firebaseAuth
    ) {
        $this->userRepository = $userRepository;
        $this->firebaseAuth = $firebaseAuth;
    }

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
            $newUserAccount->email_verified_at = now();
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

                    $toPath = storage_path() . '/app/public/photos/' . $newUserAccountId . '/';
                    $toPathForStorage = '/public/photos/' . $newUserAccountId . '/';
                    if (!Storage::exists($toPathForStorage)){
                        Storage::makeDirectory($toPathForStorage);
                    }
                    $fileName = str_random() . '.' . $extension;
                    $path = '/app/photos/' . $newUserAccountId . '/' . $fileName;
                    Image::make(storage_path() . $path)
                        ->fit(50, 50)
                        ->save($toPath . $fileName);
                }

                $token = JWTAuth::fromUser($newUserAccount);

                $userArr = $this->userRepository->transformUser($newUserAccount);

                if (isset($userArr)) {

                    try {
                        $customToken = $this->firebaseAuth
                            ->createCustomToken((string)$user->id);
                    } catch (\Exception $exception) {
                        return $this->responseError();
                    }
                }

                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $userArr,
                    'id_token' => (string) $customToken,
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
