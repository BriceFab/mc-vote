<?php

namespace App\Repository;

use App\Classes\Enum\EnumParamKey;
use App\Classes\Enum\EnumParamType;
use App\Entity\Parameter;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parameter[]    findAll()
 * @method Parameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parameter::class);
    }

    /**
     * Retourne la valeur du param
     * @param $key
     * @return bool|null|string
     */
    public function findActiveParam($key)
    {
        $now = new DateTime();
        $now->setTime(0, 0);

        try {

            /** @var Parameter $param */
            $param = $this->createQueryBuilder('p')
                ->andWhere('p.param_key = :key')
                ->andWhere('p.expirationDate is null OR p.expirationDate >= :now')
                ->orderBy('p.expirationDate', 'DESC')
                ->setMaxResults(1)
                ->setParameter('key', $key)
                ->setParameter('now', $now)
                ->getQuery()
                ->getOneOrNullResult();

            if (!is_null($param)) {
                return $param->getFormattedValue();
            }

            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

}
