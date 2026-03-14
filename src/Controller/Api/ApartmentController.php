<?php

namespace App\Controller\Api;

use App\Entity\City;
use App\Entity\Apartment;
use App\Helper\AppHelper;
use App\Service\ApartmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/apartments')]
final class ApartmentController extends AbstractController
{
    private $key;
    private $service;
    private $appHelper;

    public function __construct(
        AppHelper $appHelper,
        ApartmentService $apartmentService)
    {
        $this->key = 'apartment';
        $this->appHelper = $appHelper;
        $this->service = $apartmentService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->appHelper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Apartment|null $apartment): JsonResponse
    {
        if (!$apartment) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->appHelper->serialize($apartment, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/city/{id}', methods: ['GET'])]
    public function getByCity(City|null $city): JsonResponse
    {
        if (!$city) return new JsonResponse(['error' => 'Not found'], 404);
        
        $apartments = $this->appHelper->serialize(
            $this->service->getApartmentsByCity($city->getId()), $this->key
        );

        return new JsonResponse($apartments, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Apartment|null $apartment): JsonResponse
    {
        if (!$apartment) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $apartment);

        return new JsonResponse(200);
    }
}
