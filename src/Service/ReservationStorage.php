<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;

class ReservationStorage
{
    private ?Reservation $reservation = null;

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(Reservation $reservation): void
    {
        $this->reservation = $reservation;
    }
}
