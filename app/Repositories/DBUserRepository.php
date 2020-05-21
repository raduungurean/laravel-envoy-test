<?php


namespace App\Repositories;

use DB;

class DBUserRepository implements UserRepository
{
    public function getGroups(int $userId)
    {
        $sql = 'SELECT g.*, COUNT(ug1.user_id) as group_users
        	FROM users u
        	INNER JOIN user_group ug on ug.user_id = u.id
            INNER JOIN groups g on g.id = ug.group_id
            INNER JOIN user_group ug1 on ug1.group_id = ug.group_id
        WHERE u.id = :userId
        GROUP BY ug.group_id';

        return DB::select( DB::raw( $sql ), array(
            'userId' => $userId,
        ));
    }
}
