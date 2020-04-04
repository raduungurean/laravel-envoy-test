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
            $id = $user->id;

            if ($id) {
                DB::table('user_group')->insert(
                    ['user_id' => $id, 'group_id' => 1, 'created_at' => now(), 'updated_at' => now()]
                );
                if ($jsonUser->photo) {
                    $photoName = last(explode('/', $jsonUser->photo));
                    $contents = file_get_contents($jsonUser->photo);
                    Storage::disk('local')->put('photos/' . $id . '/' . $photoName, $contents);
                }
            }

            $this->command->info('added ' . $user->username);
        }

    }
}
