<?php

namespace App\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

final class MenuBuilder
{
    private $factory;
    private $entityManager;

    public function __construct(FactoryInterface $factory, EntityManagerInterface $entityManager)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    public function createMainMenuLeft(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav');

        $menu->addChild('Home', [
            'route' => 'home',
            'labelAttributes' => [
                'class' => 'class1',
            ],
            'attributes' => [
                'class' => 'class2',
            ],
            'linkAttributes' => [
                'class' => 'class3',
            ],
        ])
            ->setAttribute('icon', 'fa fa-list');

        $menu->addChild('Latest Blog Post', [
            'route' => 'app_login',
        ]);

        // create another menu item
//        $menu->addChild('About Me', ['route' => 'app_register'])
//            ->setChildrenAttribute('class', 'navbar-nav');
//        $menu['About Me']->addChild('Edit profile', ['route' => 'home']);

        return $menu;
    }

    public function createMainMenuRight(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav');

        $menu->addChild('Test', [
            'route' => 'app_login',
        ]);

        return $menu;
    }
}