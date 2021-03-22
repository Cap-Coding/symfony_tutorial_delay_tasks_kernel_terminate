<?php

declare(strict_types=1);

namespace App\Dto\Transformer\Request;

use App\Dto\Transformer\AbstractDtoTransformer;

class ReservationRequestDtoTransformer extends AbstractDtoTransformer
{
    public function transformFromObject($reservation): array
    {
        return [
            'id' => $reservation->getId(),
        ];
    }
}