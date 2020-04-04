<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Mail\RegisteredPleaseActivate;
use Illuminate\Http\Request;
use App\User;
use Mail;

class TestAction extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find(1);

        Mail::to($user)->send(new RegisteredPleaseActivate($user));

        if (Mail::failures()) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry! Please try again latter',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Great! Successfully send in your mail',
            ]);
        }
    }
}
