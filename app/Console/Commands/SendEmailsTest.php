<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class SendEmailsTest extends Command
{
    protected $signature = 'email:cron-email';

    protected $description = 'Cron email command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Mail::raw('Hi, welcome user!', function ($message) {
            $message->to(config('app.email'))
            ->subject('check now - cron run');
        });
    }
}
