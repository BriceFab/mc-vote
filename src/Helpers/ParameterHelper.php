<?php

namespace App\Helpers;

use App\Classes\Enum\EnumParamProviderType;
use App\Classes\Enum\EnumParamType;
use App\Entity\Parameter;
use App\Repository\ParameterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Pattern: Singleon
 * But: Eviter d'aller chercher plusieur fois les mêmes paramètres en base de donnée
 * Autheur: Riva Fabrice
 * Class ParameterHelper
 * @package App\Helpers
 */
class ParameterHelper
{
    private static $instance = null;

    private $doctrine;
    private $params = [];

    private function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public static function getInstance($doctrine): ?ParameterHelper
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($doctrine);
        }

        return self::$instance;
    }

    public function getDatabaseParam(string $key)
    {
        $default_value = $key;

        if (is_null($this->doctrine)) {
            return $default_value;
        }

        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        /** @var ParameterRepository $repo */
        $repo = $this->doctrine->getRepository(Parameter::class);

        $value = $repo->findActiveParam($key);

        if (!is_null($value)) {
            $this->params[$key] = $value;
        }

        if (!is_null($value)) {
            return $value;
        } else {
            return null;
        }
    }

}