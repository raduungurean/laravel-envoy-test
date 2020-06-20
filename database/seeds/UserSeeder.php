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
                $groupId = 1;
                DB::table('user_group')->insert(
                    ['user_id' => $id, 'group_id' => $groupId, 'created_at' => now(), 'updated_at' => now()]
                );
                if ($jsonUser->photo) {
                    $photoName = last(explode('/', $jsonUser->photo));
                    $user->photo = $photoName;
                    $user->save();
                    $contents = file_get_contents($jsonUser->photo);
                    $photoPath = 'photos/' . $id . '/' . $photoName;
                    Storage::disk('local')->put($photoPath, $contents);
                    $toPath = storage_path() . '/app/public/photos/' . $id . '/';
                    $toPathForStorage = '/public/photos/' . $id . '/';
                    if(!Storage::exists($toPathForStorage)){
                        Storage::makeDirectory($toPathForStorage);
                    }
                    Image::make(storage_path() . '/app/' . $photoPath)
                        ->fit(50, 50)
                        ->save($toPath . $photoName);
                }
                DB::table('role_user_group')->insert(
                    ['role_id' => 4, 'user_id' => $id, 'group_id' => $groupId]
                );
            }

            $this->command->info('added ' . $user->username);
        }

        DB::table('users')
            ->where('id', 1)
            ->update(['email_verified_at' => date('Y-m-d H:i:s')]);

    }
}
