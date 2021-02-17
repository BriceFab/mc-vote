<?php

namespace App\EventSubscriber;

use App\Classes\Enum\EnumModifier;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class EntitySubscriber implements EventSubscriber
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        $now = new DateTime();
        if (method_exists($entity, 'setCreatedAt') && method_exists($entity, 'getCreatedAt') && is_null($entity->getCreatedAt())) {
            $entity->setCreatedAt($now);
        }

        if (method_exists($entity, 'setCreatedBy') && method_exists($entity, 'getCreatedBy') && is_null($entity->getCreatedBy())) {
            $entity->setCreatedBy($this->userModifier());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        $now = new DateTime();
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt($now);
        }

        if (method_exists($entity, 'setUpdatedBy')) {
            $entity->setUpdatedBy($this->userModifier());
        }
    }

    private function userModifier(): string
    {
        $user = $this->security->getUser();
        if (!is_null($user)) {
            return $user->getUsername();
        }
        return EnumModifier::SYSTEM;
    }

}
