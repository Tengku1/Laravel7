<?php

namespace App\Role;

class UserRole
{

    const ROLE_ADMIN = 'Admin';
    const ROLE_MASTER = 'Master';

    /**
     * @var array
     */
    protected static $roleHierarchy = [
        self::ROLE_MASTER => ['*'],
        self::ROLE_ADMIN => []
    ];

    public static function getAllowedRoles(string $role)
    {
        if (isset(self::$roleHierarchy[$role])) {
            return self::$roleHierarchy[$role];
        }

        return [];
    }

    /***
     * @return array
     */
    public static function getRoleList()
    {
        return [
            static::ROLE_ADMIN => 'Admin',
            static::ROLE_MASTER => 'Master',
        ];
    }
}
