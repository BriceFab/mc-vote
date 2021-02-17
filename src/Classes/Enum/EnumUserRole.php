<?php

namespace App\Classes\Enum;

use ReflectionClass;

class EnumUserRole
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public static function getList(): array
    {
        $class = new ReflectionClass(self::class);
        return $class->getConstants();
    }
}