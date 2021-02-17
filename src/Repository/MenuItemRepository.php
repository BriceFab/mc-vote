<?php

namespace App\Repository;

use App\Entity\MenuItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuItem[]    findAll()
 * @method MenuItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuItemRepository extends ServiceEntityRepository
{
    /** @var boolean */
    protected $cacheLoaded;

    /** @var array */
    protected $nameArray;

    public function __construct(ManagerRegistry $registry)
    {
        $this->cacheLoaded = false;
        $this->nameArray = [];

        parent::__construct($registry, MenuItem::class);
    }

    public function getMenuItemByName($name)
    {
        $this->populateCache();

        if (!array_key_exists($name, $this->nameArray)) {
            return null;
        }

        return $this->nameArray[$name];
    }

    /**
     * Will query all the menu items at and sort them for the cache.
     */
    protected function populateCache()
    {
        if (!$this->cacheLoaded) {
            // Query two levels deep.
            $allMenuItems = $this->createQueryBuilder('m')
                ->addSelect('children')
                ->leftJoin('m.children', 'children')
                ->getQuery()
                ->enableResultCache(3600)   //1 heure
                ->getResult();

            foreach ($allMenuItems as $menuItem) {
                $this->nameArray[$menuItem->getName()] = $menuItem;
            }

            $this->cacheLoaded = true;
        }
    }
}
