<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\ReservationApartment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationApartmentRepository;

class ReservationApartmentService
{
    private $repository;
    private $entityManager;
    private $reservationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationService $reservationService,
        ReservationApartmentRepository $reservationApartmentRepository)
    {
        $this->entityManager = $entityManager;
        $this->reservationService = $reservationService;
        $this->repository = $reservationApartmentRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persistReservationsItem(Request $request, ReservationApartment|null $reservation): ?ReservationApartment
    {
        if ($reservation) $this->persist($request, $reservation);

        return $reservation;
    }

    public function persist(Request $request, ReservationApartment $reservationApartment): void
    {
        $data = json_decode($request->getContent(), true);
        $reservation = $this->reservationService->persistReservationItem($request, $reservationApartment->getReservation());
        $reservationApartment
            //-> this add apartment
            ->setReservation($reservation ?? $reservationApartment->getReservation());
        ;

        $this->entityManager->persist($reservationApartment);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $reservation = new ReservationApartment();
        $reservation->setReservation(new Reservation());

        $this->persist($request, $reservation);
    }

    public function edit(Request $request, ReservationApartment $reservation): void
    {
        $this->persist($request, $reservation);
    }
}
