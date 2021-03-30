<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\Transformer\DtoTransformerInterface;
use App\Entity\Reservation;
use App\Exception\ApiClientException;
use App\Service\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class OnReservationRetrievalListener
{
    private DtoTransformerInterface $requestReservationTransformer;
    private ApiClient $apiClient;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(
        DtoTransformerInterface $requestReservationTransformer,
        ApiClient $apiClient,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    ) {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->requestReservationTransformer = $requestReservationTransformer;
        $this->logger = $logger;
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

//        if ('GET' !== $request->getMethod()) {
        if ('api_v1_reservation_show' !== $request->get('_route')) {
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
                'v1/very/slow/endpoint',
                $requestDto
            );
        } catch (ApiClientException $e) {
            $message = 'Error during API call: ' . $e->getMessage();
            $this->logger->error($message);

            return;
        }

        // Do something with response
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

        return $this->entityManager
            ->getRepository(Reservation::class)
            ->findOneBy([ 'id' => $reservationId ]);
    }
}
