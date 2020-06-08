<?php


namespace App\Repositories;

use App\Invite;

class DBInviteRepository implements InviteRepository
{
    public function checkByGroup(string $email, int $groupId, string $accepted = 'no')
    {
        return Invite::where('to_email', $email)
            ->where('group_id', $groupId)
            ->where('accepted', $accepted)
            ->exists();
    }

    public function add(string $email, int $groupId, int $userId)
    {
        $invite = new Invite();
        $invite->by = $userId;
        $invite->to_email = $email;
        $invite->group_id = $groupId;
        $invite->hash = hash_hmac('sha256', str_random(40), config('app.key'));;

        return $invite->save();
    }

    public function get(string $email, int $groupId)
    {
        return Invite::where('to_email', $email)
            ->where('group_id', $groupId)
            ->first();
    }
}
