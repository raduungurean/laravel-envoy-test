<?php

namespace App\Repositories;

interface InviteRepository
{
    public function checkByGroup(string $email, int $groupId, string $accepted = 'no');
    public function add(string $email, int $groupId, int $by);
    public function get(string $email, int $groupId);
}
