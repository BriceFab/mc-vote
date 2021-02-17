<?php

namespace App\Controller\Common;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseController extends AbstractController implements ServiceSubscriberInterface
{
    public function trans(string $key, array $params = [], string $domain = null, string $locale = null): string
    {
        if (!$this->container->has('translator')) {
            throw new ServiceNotFoundException('translator.', null, null, [], sprintf('translator service not found'));
        }

        /** @var TranslatorInterface $translor */
        $translor = $this->container->get('translator');

        return $translor->trans($key, $params, $domain, $locale);
    }

    public function addFlashTrans(string $type, string $key, array $params = [], string $domain = null, string $locale = null)
    {
        $this->addFlash($type, $this->trans($key, $params, $domain, $locale));
    }

    public static function getSubscribedServices(): array
    {
        $baseServices = parent::getSubscribedServices();

        $service = [
            'translator' => '?' . TranslatorInterface::class,
        ];

        return array_unique(array_merge($service, $baseServices));
    }

}