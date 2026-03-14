<?php

namespace App\Controller\Api;

use App\Helper\AppHelper;
use App\Entity\ReservationVehicle;
use App\Service\ReservationVehicleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/vehicle/reservations')]
final class ReservationVehicleController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        ReservationVehicleService $reservationVehicleService)
    {
        $this->helper = $appHelper;
        $this->key = 'reservation_vehicle';
        $this->service = $reservationVehicleService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(ReservationVehicle|null $reservation): JsonResponse
    {
        if (!$reservation) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($reservation, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, ReservationVehicle|null $reservation): JsonResponse
    {
        if (!$reservation) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $reservation);

        return new JsonResponse(200);
    }
}
