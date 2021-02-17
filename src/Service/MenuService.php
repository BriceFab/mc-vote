<?php

namespace App\Service;

use App\Helpers\FunctionHelper;
use Knp\Menu\MenuItem;

class MenuService
{

    public function addCssClass(MenuItem $menuItem, string $attribute, $classes)
    {
        $getter = FunctionHelper::findGetter($menuItem, $attribute);
        $setter = FunctionHelper::findSetter($menuItem, $attribute);

        $property = "class";

        $values = $menuItem->{$getter}($property);
        if (is_null($values)) {
            $values = "";
        }

        if (is_string($classes)) {
            $values .= " $classes";
        }

        if (is_array($classes)) {
            foreach ($classes as $class) {
                $values .= " $class";
            }
        }

        $menuItem->{$setter}($property, $values);
    }

}