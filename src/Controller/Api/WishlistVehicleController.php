<?php

namespace App\Controller\Api;

use App\Entity\WishlistVehicle;
use App\Service\WishlistVehicleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/vehicle/wishlist')]
final class WishlistVehicleController extends AbstractController
{
    private $service;

    public function __construct(WishlistVehicleService $wishlistVehicleService)
    {
        $this->service = $wishlistVehicleService;
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, WishlistVehicle|null $wishlist): JsonResponse
    {
        if (!$wishlist) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $wishlist);

        return new JsonResponse(200);
    }
}
