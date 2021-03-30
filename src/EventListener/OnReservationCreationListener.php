<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\Transformer\DtoTransformerInterface;
use App\Entity\Reservation;
use App\Exception\ApiClientException;
use App\Service\ApiClient;
use App\Service\ReservationStorage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class OnReservationCreationListener
{
    private ApiClient $apiClient;
    private EntityManagerInterface $entityManager;
    private DtoTransformerInterface $requestReservationTransformer;
    private ReservationStorage $reservationStorage;
    private LoggerInterface $logger;

    public function __construct(
        ApiClient $apiClient,
        EntityManagerInterface $entityManager,
        DtoTransformerInterface $reservationTransformer,
        ReservationStorage $reservationStorage,
        LoggerInterface $logger
    ) {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->requestReservationTransformer = $reservationTransformer;
        $this->reservationStorage = $reservationStorage;
        $this->logger = $logger;
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        $allowedRoutes = [
            'api_v1_reservation_create',
            'api_v2_reservation_create',
        ];

        if (!\in_array($request->get('_route'), $allowedRoutes, true)) {
            return;
        }

        $reservation = $this->getReservation($event);

        if (!$reservation) {
            return;
        }

        $requestDto = $this->requestReservationTransformer->transformFromObject($reservation);

        try {
            $response = $this->apiClient->send(
                'POST',
                'v1/very/slow/endpoint',
                $requestDto
            );
        } catch (ApiClientException $e) {
            $this->logger->error('Error during API call: ' . $e->getMessage());
        }

        // Do something with response
    }

    private function getReservation(TerminateEvent $event): ?Reservation
    {
//        return $this->getReservationFromResponse($event->getResponse());
        return $this->getReservationFromService();
    }

    private function getReservationFromResponse(Response $response): ?Reservation
    {
        $content = \json_decode($response->getContent(), true);

        if (!$content || empty($content['id'])) {
            return null;
        }

        return $this->entityManager->getRepository(Reservation::class)->findOneBy([ 'id' => $content['id'] ]);
    }

    private function getReservationFromService(): ?Reservation
    {
        return $this->reservationStorage->getReservation();
    }
}
