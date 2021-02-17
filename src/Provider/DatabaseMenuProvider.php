<?php

namespace App\Provider;

use App\Repository\MenuItemRepository;
use InvalidArgumentException;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;

class DatabaseMenuProvider implements MenuProviderInterface
{
    /**
     * @var MenuItemRepository
     */
    protected $repository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->repository = $menuItemRepository;
    }

    public function get(string $name, array $options = []): ItemInterface
    {
        $menuItem = $this->repository->getMenuItemByName($name);

        if ($menuItem === null) {
            throw new InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        return $menuItem;
    }

    public function has(string $name, array $options = []): bool
    {
        $menuItem = $this->repository->getMenuItemByName($name);

        if ($menuItem === null) {
            return false;
        }

        return true;
    }
}