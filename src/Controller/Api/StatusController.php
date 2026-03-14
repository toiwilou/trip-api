<?php

namespace App\Controller\Api;

use App\Entity\Status;
use App\Helper\AppHelper;
use App\Service\StatusService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/status')]
final class StatusController extends AbstractController
{
    private $key;
    private $service;
    private $appHelper;

    public function __construct(
        AppHelper $appHelper,
        StatusService $statusService)
    {
        $this->key = 'status';
        $this->appHelper = $appHelper;
        $this->service = $statusService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->appHelper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Status|null $status): JsonResponse
    {
        if (!$status) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->appHelper->serialize($status, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Status|null $status): JsonResponse
    {
        if (!$status) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $status);

        return new JsonResponse(200);
    }
}
