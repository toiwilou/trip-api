<?php

namespace App\Controller\Api;

use App\Helper\AppHelper;
use App\Entity\ReservationApartment;
use App\Service\ReservationApartmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/apartment/reservations')]
final class ReservationApartmentController extends AbstractController
{
    private $key;
    private $service;
    private $appHelper;

    public function __construct(
        AppHelper $appHelper,
        ReservationApartmentService $reservationApartmentService)
    {
        $this->appHelper = $appHelper;
        $this->key = 'reservation_apartment';
        $this->service = $reservationApartmentService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->appHelper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(ReservationApartment|null $reservation): JsonResponse
    {
        if (!$reservation) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->appHelper->serialize($reservation, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, ReservationApartment|null $reservation): JsonResponse
    {
        if (!$reservation) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $reservation);

        return new JsonResponse(200);
    }
}
