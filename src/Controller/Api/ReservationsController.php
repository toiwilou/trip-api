<?php

namespace App\Controller\Api;

use App\Helper\AppHelper;
use App\Entity\Reservations;
use App\Service\ReservationsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/reservations')]
final class ReservationsController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        ReservationsService $reservationsService)
    {
        $this->helper = $appHelper;
        $this->key = 'reservations';
        $this->service = $reservationsService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Reservations|null $reservations): JsonResponse
    {
        if (!$reservations) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($reservations, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Reservations|null $reservations): JsonResponse
    {
        if (!$reservations) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $reservations);

        return new JsonResponse(200);
    }
}
