<?php

namespace App\Twig;

use App\Classes\Enum\EnumParamProviderType;
use App\Helpers\ParameterHelper;
use App\Service\ParameterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctionsExtension extends AbstractExtension
{
    private $router;
    private $em;
    private $parameterService;

    public function __construct(RouterInterface $router, EntityManagerInterface $em, ParameterService $parameterService)
    {
        $this->router = $router;
        $this->em = $em;
        $this->parameterService = $parameterService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('checkRouteExists', [$this, 'checkRouteExists']),
            new TwigFunction('getParameter', [$this, 'getParameter']),
            new TwigFunction('test', [$this, 'test']),
        ];
    }

    public function checkRouteExists($route_name): bool
    {
        return ($this->router->getRouteCollection()->get($route_name) !== null);
    }

    public function getParameter(string $key, string $type = EnumParamProviderType::ENVIRONNEMENT): ?string
    {
        $paramHelper = ParameterHelper::getInstance($this->em);

        if ($type === EnumParamProviderType::ENVIRONNEMENT) {
            $this->parameterService->getParamFromType($key, $type);
        }

        return $paramHelper->getDatabaseParam($key);
    }

}
