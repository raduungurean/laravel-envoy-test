<?php

class UserSeeder extends MySeeder
{
    public function run()
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $this->importUrl . '/get-users');

        $jsonUsers = json_decode($res->getBody());

        foreach ($jsonUsers as $jsonUser) {
            $user = new \App\User();
            $user->old_id = $jsonUser->id;
            $user->username = $jsonUser->username;
            $user->first_name = $jsonUser->first_name;
            $user->last_name = $jsonUser->last_name;
            $user->email = $jsonUser->email;
            $user->photo = $jsonUser->photo;
            $user->created_at = $jsonUser->created_at;
            $user->updated_at = $jsonUser->updated_at;
            $user->password = $jsonUser->password;
            $user->save();
            $this->command->info('added ' . $user->username);
        }
    }
}
