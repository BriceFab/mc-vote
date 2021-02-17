<?php

namespace App\Menu;

use Knp\Menu\Factory\CoreExtension;
use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use SplPriorityQueue;

class DatabaseMenuFactory implements FactoryInterface
{
    protected $menuItemEntityName;

    /**
     * @var SplPriorityQueue|ExtensionInterface[]
     */
    protected $extensions;

    public function __construct(ExtensionInterface $routingExtension, $menuItemEntityName)
    {
        $this->menuItemEntityName = $menuItemEntityName;
        $this->extensions = new SplPriorityQueue();
        $this->addExtension(new CoreExtension(), -20);
        $this->addExtension($routingExtension, -10);
    }

    /**
     * Creates the menu item.
     *
     * @param string $name
     * @param array $options
     * @return ItemInterface
     */
    public function createItem(string $name, array $options = []): ItemInterface
    {
        foreach (clone $this->extensions as $extension) {
            $options = $extension->buildOptions($options);
        }

        $class = $this->menuItemEntityName;
        $item = new $class($name, $this);

        foreach (clone $this->extensions as $extension) {
            $extension->buildItem($item, $options);
        }

        return $item;
    }

    /**
     * Adds a factory extension
     *
     * @param ExtensionInterface $extension
     * @param integer $priority
     */
    public function addExtension(ExtensionInterface $extension, $priority = 0)
    {
        $this->extensions->insert($extension, $priority);
    }
}