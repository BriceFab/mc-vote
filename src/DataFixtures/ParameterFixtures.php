<?php

namespace App\DataFixtures;

use App\Classes\Enum\EnumParamKey;
use App\Classes\Enum\EnumParamType;
use App\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ParameterFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $param = new Parameter();
        $param->setParamKey(EnumParamKey::SITE_NAME);
        $param->setParamType(EnumParamType::STRING);
        $param->setValue("ScandiCraft");
        $param->setDescription("Nom du site internet");
        $manager->persist($param);

        $param = new Parameter();
        $param->setParamKey(EnumParamKey::SITE_LOGO);
        $param->setParamType(EnumParamType::IMAGE);
        $param->setValue(null);
        $param->setDescription("Logo du site");
        $manager->persist($param);

        $param = new Parameter();
        $param->setParamKey(EnumParamKey::UNKNOWN_BACKGROUND);
        $param->setParamType(EnumParamType::IMAGE);
        $param->setValue(null);
        $param->setDescription("Image de remplacement");
        $manager->persist($param);

        $param = new Parameter();
        $param->setParamKey(EnumParamKey::ADMIN_ACTION_ICON);
        $param->setParamType(EnumParamType::BOOLEAN);
        $param->setValue(true);
        $param->setDescription("Admin: Les actions ont des icones");
        $manager->persist($param);

        $param = new Parameter();
        $param->setParamKey(EnumParamKey::ADMIN_HEADER_MENU);
        $param->setParamType(EnumParamType::BOOLEAN);
        $param->setValue(true);
        $param->setDescription("Admin a le même menu que le site");
        $manager->persist($param);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['base', 'param'];
    }
}
