<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlashBagService
{
    private $flashBag;
    private $translator;

    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function add(string $type, string $message_key, array $message_parameters = [])
    {
        $this->flashBag->add($type, $this->translator->trans($message_key, $message_parameters));
    }

}