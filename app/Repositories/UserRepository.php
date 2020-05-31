<?php


namespace App\Repositories;


interface UserRepository
{
    public function getGroups(int $userId);
    public function getCountGroups(int $userId);
}
