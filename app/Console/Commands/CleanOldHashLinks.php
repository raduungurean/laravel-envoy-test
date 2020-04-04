<?php

namespace App\Console\Commands;

use App\Hash;
use Illuminate\Console\Command;

class CleanOldHashLinks extends Command
{
    protected $signature = 'hashes:clean';

    protected $description = 'Cleans the hashes table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
         Hash::where('hash_type', 'verify-account')->where('created_at', '<=', date('Y-m-d 00:00:00',strtotime("-5 days")))->delete();
         Hash::where('hash_type', 'forgot-password')->where('created_at', '<=', date('Y-m-d 00:00:00',strtotime("-1 days")))->delete();
    }
}
