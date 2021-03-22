<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\Type\ReservationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationController extends AbstractApiController
{
    public function showAction(Request $request): Response
    {
        $reservationId = $request->get('id');

        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->findOneBy([
            'id' => $reservationId,
        ]);

        if (!$reservation) {
            throw new NotFoundHttpException('Reservation not found');
        }

        $dto = $reservation;

        return $this->respond($dto);
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(ReservationType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Reservation $reservation */
        $reservation = $form->getData();

        $this->getDoctrine()->getManager()->persist($reservation);
        $this->getDoctrine()->getManager()->flush();

        $dto = $reservation;

        return $this->respond($dto);
    }
}
