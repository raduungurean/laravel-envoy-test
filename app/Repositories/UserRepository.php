<?php


namespace App\Repositories;


interface UserRepository
{
    public function getGroups(int $userId = null);
    public function inGroup(string $email, int $groupId);
    public function getCountGroups(int $userId);
    public function isEditorForGroup(int $userId, int $groupId);
    public function checkByEmail(string $email);
    public function getPendingInvites(string $email);
    public function transformUser($userObject);
}
