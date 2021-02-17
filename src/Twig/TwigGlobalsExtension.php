<?php

namespace App\Twig;

use App\Service\ParameterService;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TwigGlobalsExtension extends AbstractExtension implements GlobalsInterface
{
    private $parameterService;
    private $em;

    public function __construct(ParameterService $parameterService, EntityManagerInterface $em)
    {
        $this->parameterService = $parameterService;
        $this->em = $em;
    }

    public function getGlobals(): array
    {
        return [];
    }

}
