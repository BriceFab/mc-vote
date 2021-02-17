<?php

namespace App\Classes\Enum;

use ReflectionClass;

/**
 * Class EnumParamKey
 * @package App\Classes
 */
class EnumParamKey
{

    const SITE_NAME = 'param.site.name';
    const SITE_LOGO = 'param.site.logo';
    const UNKNOWN_BACKGROUND = 'param.unknown.background';
    const ADMIN_ACTION_ICON = 'param.admin.action.icon';
    const ADMIN_HEADER_MENU = 'param.admin.header.menu';

    public static function getList(): array
    {
        $class = new ReflectionClass(self::class);
        return $class->getConstants();
    }

}
