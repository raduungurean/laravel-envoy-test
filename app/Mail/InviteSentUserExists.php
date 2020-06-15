<?php

namespace App\Mail;

use App\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteSentUserExists extends Mailable
{
    use Queueable, SerializesModels;

    private $invite;
    private $actionUrl;
    private $user;

    public function __construct(Invite $invite, string $user)
    {
        $this->invite = $invite;
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject(config('app.name') . ' - Invitation')
            ->view('emails.invite-user-exists')->with([
                'user' => $this->user,
                'support_email' => config('app.email'),
                'app_name' => config('app.name'),
                'app_user' => config('app.user'),
            ]);
    }
}
