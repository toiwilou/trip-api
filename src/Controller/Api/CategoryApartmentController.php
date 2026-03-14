<?php

namespace App\Controller\Api;

use App\Helper\AppHelper;
use App\Entity\CategoryApartment;
use App\Service\CategoryApartmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/apartment/categories')]
final class CategoryApartmentController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        CategoryApartmentService $categoryApartmentService)
    {
        $this->helper = $appHelper;
        $this->key = 'category_apartment';
        $this->service = $categoryApartmentService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(CategoryApartment|null $category): JsonResponse
    {
        if (!$category) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($category, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, CategoryApartment|null $category): JsonResponse
    {
        if (!$category) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $category);

        return new JsonResponse(200);
    }
}
