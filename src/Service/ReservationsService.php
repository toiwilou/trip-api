<?php

namespace App\Service;

use App\Entity\Reservations;
use App\Entity\ReservationVehicle;
use App\Entity\ReservationApartment;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;

class ReservationsService
{
    private $repository;
    private $entityManager;
    private $reservationVehicleService;
    private $reservationApartmentService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationsRepository $reservationsRepository,
        ReservationVehicleService $reservationVehicleService,
        ReservationApartmentService $reservationApartmentService)
    {
        $this->entityManager = $entityManager;
        $this->repository = $reservationsRepository;
        $this->reservationVehicleService = $reservationVehicleService;
        $this->reservationApartmentService = $reservationApartmentService;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Reservations $reservations): void
    {
        $reservationApartment = $this->reservationApartmentService->persistReservationsItem($request, $reservations->getReservationApartment());
        $reservationVehicle = $this->reservationVehicleService->persistReservationsItem($request, $reservations->getReservationVehicle());
        $reservations
            ->setReservationApartment($reservationApartment ?? $reservations->getReservationApartment())
            ->setReservationVehicle($reservationVehicle ?? $reservations->getReservationVehicle())
            //->setCustomer()
        ;

        $this->entityManager->persist($reservations);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $reservations = new Reservations();
        $data = json_decode($request->getContent(), true);
        $has_apartment = $data['has_apartment'] == 'true';
        $has_vehicle = $data['has_vehicle'] == 'true';

        $reservations->setReservationApartment($has_apartment ? new ReservationApartment() : null);
        $reservations->setReservationVehicle($has_vehicle ? new ReservationVehicle() : null);

        $this->persist($request, $reservations);
    }

    public function edit(Request $request, Reservations $reservations): void
    {
        $this->persist($request, $reservations);
    }
}
