<?php

use Illuminate\Database\Seeder;

class MatchesSeeder extends Seeder
{
    public function run()
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $this->importUrl . '/get-matches');

        $jsonMatches = json_decode($res->getBody());

    }
}
