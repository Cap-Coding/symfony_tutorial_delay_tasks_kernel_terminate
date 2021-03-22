<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\Transformer\DtoTransformerInterface;
use App\Entity\Reservation;
use App\Exception\ApiClientException;
use App\Service\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class ReservationGetListener
{
    private ApiClient $apiClient;
    private EntityManagerInterface $entityManager;
    private DtoTransformerInterface $requestReservationTransformer;

    public function __construct(
        ApiClient $apiClient,
        EntityManagerInterface $entityManager,
        DtoTransformerInterface $requestReservationTransformer
    ) {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->requestReservationTransformer = $requestReservationTransformer;
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ('GET' !== $request->getMethod() || 'api_v1_reservation_show' !== $request->get('_route')) {
            return;
        }

        $reservation = $this->getReservation($event->getRequest());

        if (!$reservation) {
            return;
        }

        $requestDto = $this->requestReservationTransformer->transformFromObject($reservation);
        try {
            $response = $this->apiClient->send(
                'POST',
                'v1/very/slow/endpoint/track',
                $requestDto
            );
        } catch (ApiClientException $e) {
            // Do something with response
        }
    }

    /**
     * @param Request $request
     *
     * @return Reservation|null
     */
    private function getReservation(Request $request): ?Reservation
    {
        $reservationId = $request->get('id');

        if (!$reservationId) {
            return null;
        }

        return $this->entityManager->getRepository(Reservation::class)->findOneBy([ 'id' => $reservationId ]);
    }
}
