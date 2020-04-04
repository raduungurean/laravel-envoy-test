<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisteredPleaseActivate extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $hash;
    protected $actionUrl;

    public function __construct($user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
        $this->actionUrl = url('/api/email/verify/' . $user->id . '/' . $this->hash);
    }

    public function build()
    {
        return $this
            ->subject(config('app.name') . ' - Verify your email address')
            ->view('emails.registered-please-activate')->with([
                'user' => $this->user,
                'action_url' => $this->actionUrl,
                'support_email' => config('app.email'),
                'app_name' => config('app.name'),
                'app_user' => config('app.user'),
        ]);
    }
}
