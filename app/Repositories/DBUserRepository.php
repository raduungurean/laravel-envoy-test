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
				INNER JOIN role_user_group rug on rug.user_id = :userId1 AND g.id = rug.group_id
                INNER JOIN roles r on r.id = rug.role_id
            WHERE g.deleted_at IS NULL
            GROUP BY ug.group_id';

        $groups = DB::select( DB::raw( $sql ), array(
            'userId' => $userId,
            'userId1' => $userId,
        ));

        return collect($groups)->map(function($group) {
            $group->roles = array_map(function($role) {
                return strtolower($role);
            }, explode(',', $group->roles));
            return $group;
        });
    }

    public function getCountGroups(int $userId)
    {
        $sql = "SELECT COUNT(DISTINCT ug.id) as count
                    FROM `user_group` ug
                    INNER JOIN groups g on g.id = ug.group_id AND g.deleted_at IS NULL
                    WHERE ug.user_id = :userId";

        $count = DB::select( DB::raw( $sql ), array(
            'userId' => $userId,
        ));

        return $count[0]->count;
    }
}
