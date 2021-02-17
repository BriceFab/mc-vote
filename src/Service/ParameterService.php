<?php

namespace App\Service;

use App\Classes\Enum\EnumParamProviderType;
use App\Helpers\ParameterHelper;
use App\Repository\ParameterRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Autheur: Riva Fabrice
 * Class ParameterService
 * @package App\Service
 */
class ParameterService
{
    private $container;
    private $parameterBag;
    private $parameterRepository;

    public function __construct(ParameterBagInterface $parameterBag, ParameterRepository $parameterRepository, ContainerInterface $container)
    {
        $this->container = $container;
        $this->parameterBag = $parameterBag;
        $this->parameterRepository = $parameterRepository;
    }

    public function getDatabaseParam(string $paramKey): ?string
    {
        $doctrine = null;

        if ($this->container->has('doctrine')) {
            $doctrine = $this->container->get('doctrine');
        }

        $instance = ParameterHelper::getInstance($doctrine);

        return $instance->getDatabaseParam($paramKey);
    }

    public function getParam(string $key)
    {
        if ($this->parameterBag->has($key)) {
            return $this->parameterBag->get($key);
        }

        return null;
    }

    public function getParamFromType(string $key, string $type): ?string
    {
        if ($type === EnumParamProviderType::DATABASE) {
            return $this->getDatabaseParam($key);
        } else {
            if ($this->parameterBag->has($key)) {
                return $this->parameterBag->get($key);
            }
        }

        return null;
    }

}
