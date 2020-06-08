<?php

namespace App\Mail;

use App\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteSent extends Mailable
{
    use Queueable, SerializesModels;

    private $invite;
    private $actionUrl;
    private $user;

    public function __construct(Invite $invite, string $user)
    {
        $this->invite = $invite;
        $this->actionUrl = url('/api/accept-invite/' . $invite->hash);
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject(config('app.name') . ' - Invitation')
            ->view('emails.invite')->with([
                'action_url' => $this->actionUrl,
                'user' => $this->user,
                'support_email' => config('app.email'),
                'app_name' => config('app.name'),
                'app_user' => config('app.user'),
            ]);
    }
}
