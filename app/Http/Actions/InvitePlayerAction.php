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
        $message = $request->input('message');

        // TODO: maybe create a service
        // to also include the super admin
        // $this->hasRights('send_invitaion', $userId, $groupId)
        if (!$this->userRepository->isEditorForGroup($userId, $groupId)) {
            return response()->json(
                ['errors' => ['general' => 'unauthorized']],
                401
            );
        }

        if ($this->userRepository->inGroup($email, $groupId)) {
            return [];
        }

        $emailAlreadyIn = $this->userRepository->checkByEmail($email);

        // has an invitation but did not accept it
        if ($this->inviteRepository->checkByGroup($email, $groupId, 'no')) {
            $this->sendInvite($email, $groupId, $user, $emailAlreadyIn);
            return [];
        }

        // save the invitation
        $invited = $this->inviteRepository
            ->add($email, $groupId, $userId, $message, $emailAlreadyIn);

        if ($invited) {
            $this->sendInvite($email, $groupId, $user, $emailAlreadyIn);
        }

        return response()->json([
            'success' => true,
        ], 200);
    }

    private function sendInvite($email, $groupId, string $user, bool $emailAlreadyIn): void
    {
        $invite = $this->inviteRepository->get($email, $groupId);
        Mail::to($email)
            ->send(new InviteSent($invite, $user));
    }
}
