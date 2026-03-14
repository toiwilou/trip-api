<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\ReservationVehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationVehicleRepository;

class ReservationVehicleService
{
    private $repository;
    private $entityManager;
    private $reservationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationService $reservationService,
        ReservationVehicleRepository $reservationVehicleRepository)
    {
        $this->entityManager = $entityManager;
        $this->reservationService == $reservationService;
        $this->repository = $reservationVehicleRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persistReservationsItem(Request $request, ReservationVehicle|null $reservation): ?ReservationVehicle
    {
        if ($reservation) $this->persist($request, $reservation);

        return $reservation;
    }

    public function persist(Request $request, ReservationVehicle $reservationVehicle): void
    {
        $data = json_decode($request->getContent(), true);
        $reservation = $this->reservationService->persistReservationsItem($request, $reservationVehicle->getReservation());
        $reservationVehicle
            //-> this add vehicle
            ->setReservation($reservation ?? $reservationVehicle->getReservation())
        ;

        $this->entityManager->persist($reservationVehicle);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $reservation = new ReservationVehicle();
        $reservation->setReservation(new Reservation());

        $this->persist($request, $reservation);
    }

    public function edit(Request $request, ReservationVehicle $reservation): void
    {
        $this->persist($request, $reservation);
    }
}
