<?php

declare(strict_types=1);

namespace App\Dto\Transformer\Response;

use App\Dto\Transformer\AbstractDtoTransformer;
use App\Entity\Reservation;

class ReservationResponseDtoTransformer extends AbstractDtoTransformer
{
    /**
     * @param Reservation $reservation
     *
     * @return array
     */
    public function transformFromObject($reservation): array
    {
        return [
            'id' => $reservation->getId(),
            'name' => $reservation->getName(),
            'price' => $reservation->getPrice(),
        ];
    }
}