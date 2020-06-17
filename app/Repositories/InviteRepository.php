<?php

namespace App\Repositories;

interface InviteRepository
{
    public function checkByGroup(string $email, int $groupId, string $accepted = 'no');
    public function add(string $email, int $groupId, int $by, string $message, bool $emailAlreadyIn);
    public function get(string $email, int $groupId);
    public function getByHashAndEmail(string $hash, string $email);
}
