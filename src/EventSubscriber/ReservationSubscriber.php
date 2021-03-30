<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Reservation;
use App\Service\ReservationStorage;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class ReservationSubscriber implements EventSubscriber
{
    private ReservationStorage $reservationStorage;

    public function __construct(ReservationStorage $evolutionBag)
    {
        $this->reservationStorage = $evolutionBag;
    }

    public function getSubscribedEvents(): array
    {
        return [Events::preUpdate, Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $reservation = $eventArgs->getEntity();

        if (!$reservation instanceof Reservation) {
            return;
        }

        $this->reservationStorage->setReservation($reservation);
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $reservation = $eventArgs->getEntity();

        if (!$reservation instanceof Reservation) {
            return;
        }

        $this->reservationStorage->setReservation($reservation);
    }
}
