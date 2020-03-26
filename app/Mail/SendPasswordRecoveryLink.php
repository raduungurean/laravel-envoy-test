<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordRecoveryLink extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $hash;
    protected $actionUrl;

    public function __construct($user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
        $this->actionUrl = url('/api/password/' . $user->id . '/' . $this->hash);
    }

    public function build()
    {
        return $this
            ->subject('[' . config('app.name') . '] - Password reset')
            ->view('emails.recover-password')->with([
                'user' => $this->user,
                'action_url' => $this->actionUrl,
                'support_email' => config('app.email'),
                'app_name' => config('app.name'),
                'app_user' => config('app.user'),
            ]);
    }
}
