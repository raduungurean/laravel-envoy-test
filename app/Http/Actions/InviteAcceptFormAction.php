<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Invite;
use Illuminate\Http\Request;

class InviteAcceptFormAction extends Controller
{
    public function __invoke(Request $request, string $code)
    {
        $invite = Invite::with('group')
            ->with('user')
            ->where('hash', $code)
            ->first();

        if ($invite) {
            if ($invite->accepted === 'yes') {
                return view('invite-flow.accepted-already', ['invite' => $invite]);
            } else {
                return view('invite-flow.accept-invite-form', ['invite' => $invite]);
            }
        }

        return view('invite-flow.invite-error');
    }
}
