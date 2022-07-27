<?php

declare(strict_types=1);

namespace app\common\rbac;

class CollectionRolls
{

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_USER = 'user';

    public static function getListRole(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Суперадминистратор',
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_MODERATOR => 'Модератор',
            self::ROLE_USER => 'Пользователь',
        ];
    }

}
