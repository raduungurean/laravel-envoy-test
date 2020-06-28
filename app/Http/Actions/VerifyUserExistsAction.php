<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\User;

class VerifyUserExistsAction extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $userId = auth()->user()->id;

        $user = User::where('id', $userId)
            ->first();

        if ($user) {

            $userArr = $this->userRepository->transformUser($user);

            return response()->json([
                'success' => true,
                'user' => $userArr,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'account_does_not_exist',
        ]);
    }
}
