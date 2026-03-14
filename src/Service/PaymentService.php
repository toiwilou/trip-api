<?php

namespace App\Service;

use DateTime;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentService
{
    private $repository;
    private $entityManager;

    public function __construct(
        PaymentRepository $paymentRepository,
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $paymentRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Payment $payment): void
    {
        $data = json_decode($request->getContent(), true);
        $reservation = ''; // get real reservation
        $payment
            //->setReservation($reservation)
            ->setDate($data['date'] ? new DateTime($data['date']) : $payment->getDate())
            ->setTotal($data['total'] ? floatval($data['total']) : $payment->getTotal())
            ->setActive($data['active'] == 'true' ?? $payment->isActive())
        ;

        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Payment());
    }

    public function edit(Request $request, Payment $payment): void
    {
        $this->persist($request, $payment);
    }
}
