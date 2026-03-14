<?php

namespace App\Controller\Api;

use App\Entity\City;
use App\Entity\Vehicle;
use App\Helper\AppHelper;
use App\Service\VehicleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/vehicles')]
final class VehicleController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        VehicleService $vehicleService)
    {
        $this->key = 'vehicle';
        $this->helper = $appHelper;
        $this->service = $vehicleService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Vehicle|null $vehicle): JsonResponse
    {
        if (!$vehicle) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($vehicle, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/city/{id}', methods: ['GET'])]
    public function getByCity(City|null $city): JsonResponse
    {
        if (!$city) return new JsonResponse(['error' => 'Not found'], 404);
        
        $vehicles = $this->helper->serialize(
            $this->service->getVehiclesByCity($city->getId()), $this->key
        );

        return new JsonResponse($vehicles, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Vehicle|null $vehicle): JsonResponse
    {
        if (!$vehicle) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $vehicle);

        return new JsonResponse(200);
    }
}
