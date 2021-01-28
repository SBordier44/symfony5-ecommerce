<?php

declare(strict_types=1);

namespace App\EventListener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TimestampEntitySubscriber implements EventSubscriber
{

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if (method_exists($args->getObject(), 'setCreatedAt')) {
            $args->getObject()->setCreatedAt(new DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if (method_exists($args->getObject(), 'setUpdatedAt')) {
            $args->getObject()->setUpdatedAt(new DateTime());
        }
    }
}
