<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;

class MenuFixtures extends Fixture implements FixtureGroupInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $factory = $this->container->get('database_menu.factory');

        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'navbar-nav');

        $menu->addChild('Home', array('route' => 'home'));
        $menu->addChild('About Me', array(
            'route' => 'home'));

        // Another way to add children ...
        $parentMenuItem = $factory->createItem('Parent', array('route' => 'home'));
        $parentMenuItem->addChild('Grandchild', array('route' => 'home'));

        $menu->addChild($parentMenuItem);

        $manager->persist($menu);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['base', 'menu'];
    }
}
