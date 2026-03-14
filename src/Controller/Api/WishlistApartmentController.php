<?php

namespace App\Controller\Api;

use App\Entity\WishlistApartment;
use App\Service\WishlistApartmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/apartment/wishlist')]
final class WishlistApartmentController extends AbstractController
{
    private $service;

    public function __construct(WishlistApartmentService $wishlistApartmentService)
    {
        $this->service = $wishlistApartmentService;
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, WishlistApartment|null $wishlist): JsonResponse
    {
        if (!$wishlist) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $wishlist);

        return new JsonResponse(200);
    }
}
