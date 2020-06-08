<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use App\Mail\InviteSent;
use App\Repositories\InviteRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Mail;

class InvitePlayerAction extends Controller
{
    private $userRepository;
    private $inviteRepository;

    public function __construct(
        UserRepository $userRepository,
        InviteRepository $inviteRepository
    ) {
        $this->userRepository = $userRepository;
        $this->inviteRepository = $inviteRepository;
    }

    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user()->firstName . ' ' . Auth::user()->lastName;

        $validator = Validator::make($request->all(), [
            'email_address' => 'required|email',
            'group' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                400
            );
        }

        $groupId = $request->input('group');
        $email = $request->input('email_address');

        if (!$this->userRepository->isEditorForGroup($userId, $groupId)) {
            return response()->json(
                ['errors' => ['general' => 'unauthorized']],
                401
            );
        }

        if ($this->userRepository->inGroup($email, $groupId)) {
            return [];
        }

        // TODO: maybe create a service
        // to also include the super admin
        // $this->hasRights('send_invitaion', $userId, $groupId)
        if ($this->inviteRepository->checkByGroup($email, $groupId, 'no')) {
            // TODO: maybe resend the email here by only 3-4 times
            return [];
        }

        $invited = $this->inviteRepository->add($email, $groupId, $userId);

        if ($invited) {
            $invite = $this->inviteRepository->get($email, $groupId);
            Mail::to($email)
                ->send(new InviteSent($invite, $user));
        }

        return response()->json([
            'success' => true,
        ], 200);
    }
}
