<?php

namespace App\Service;

use DateTime;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;

class ReservationService
{
    private $repository;
    private $statusService;
    private $entityManager;

    public function __construct(
        StatusService $statusService,
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository)
    {
        $this->statusService = $statusService;
        $this->entityManager = $entityManager;
        $this->repository = $reservationRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persistReservationItem(Request $request, Reservation $reservation): Reservation
    {
        $this->persist($request, $reservation);

        return $reservation;
    }

    public function persist(Request $request, Reservation $reservation): void
    {
        $data = json_decode($request->getContent(), true);
        $status = $this->statusService->getById($data['status']);
        $reservation
            ->setBeginDate($data['begin_date'] ? new DateTime($data['begin_date']) : $reservation->getBeginDate())
            ->setEndDate($data['end_date'] ? new DateTime($data['end_date']) : $reservation->getEndDate())
            ->setStatus($status ?? $reservation->getStatus())
            ->setActive($data['active'] == 'true' ?? $reservation->isActive())
        ;

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Reservation());
    }

    public function edit(Request $request, Reservation $reservation): void
    {
        $this->persist($request, $reservation);
    }
}
