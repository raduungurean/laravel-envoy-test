<?php


namespace App\Repositories;


interface UserRepository
{
    public function getGroups(int $userId);
}
