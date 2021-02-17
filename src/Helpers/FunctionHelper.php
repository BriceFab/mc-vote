<?php

namespace App\Helpers;

class FunctionHelper
{

    public static function findGetter($object, string $property): ?string
    {
        return self::findObjectGetterSetterProperty($object, $property, true);
    }

    public static function findSetter($object, string $property): ?string
    {
        return self::findObjectGetterSetterProperty($object, $property, false);
    }

    public static function findObjectGetterSetterProperty($object, string $property, bool $getter): ?string
    {
        $methodStarts = "get";
        if (!$getter) {
            $methodStarts = "set";
        }
        $methodName = $methodStarts . ucfirst($property);

        if (method_exists($object, $methodName)) {
            return $methodName;
        }

        $class = get_class($object);
        $methods = get_class_methods($class);

        $methods = array_filter($methods, function ($method) use ($property) {
            return str_contains(strtolower($method), strtolower($property));
        });

        if (in_array($methodName, $methods)) {
            return $methodName;
        }

        return null;
    }

}