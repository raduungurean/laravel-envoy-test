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
        if ($this->check()) {
            Mail::raw('Hi, welcome user!', function ($message) {
                $message->to(config('app.email'))
                    ->subject('check now - cron run');
            });
        }
    }

    private function check()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('app.test_url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "delivery_point=^&locality_id=^&address_id=5099182^&availability_time_id=1^&request_no=5^&action_token=9e3c7845b5eeef08e9440b086ebea4964c8f8a17",
            CURLOPT_HTTPHEADER => array(
                "Connection: keep-alive",
                "Accept: */*",
                "Sec-Fetch-Dest: empty",
                "X-Requested-With: XMLHttpRequest",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36",
                "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
                "Sec-Fetch-Site: same-origin",
                "Sec-Fetch-Mode: cors",
                "Accept-Language: ro-RO,ro;q=0.9,en-US;q=0.8,en;q=0.7",
                "Content-Type: text/plain",
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (strpos($response, 'toate intervalele de livrare sunt ocupate')) {
            return false;
        }

        return true;
    }
}
