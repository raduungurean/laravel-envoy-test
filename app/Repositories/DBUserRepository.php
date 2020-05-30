<?php


namespace App\Repositories;

use DB;

class DBUserRepository implements UserRepository
{
    public function getGroups(int $userId)
    {
        $sql = 'SELECT g.*, COUNT(DISTINCT ug1.user_id) as group_users, GROUP_CONCAT(DISTINCT r.name) as roles
        	FROM groups g
                INNER JOIN user_group ug on ug.group_id = g.id
                INNER JOIN users u on u.id = ug.user_id AND u.id = :userId
                LEFT JOIN user_group ug1 on ug1.group_id = ug.group_id
				INNER JOIN role_user_group rug on rug.user_id = :userId AND g.id = rug.group_id
                INNER JOIN roles r on r.id = rug.role_id
            WHERE g.deleted_at IS NULL
            GROUP BY ug.group_id';

        return DB::select( DB::raw( $sql ), array(
            'userId' => $userId,
        ));
    }
}
