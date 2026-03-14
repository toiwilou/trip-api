<?php

namespace App\Controller\Api;

use App\Entity\History;
use App\Helper\AppHelper;
use App\Service\HistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/histories')]
final class HistoryController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        HistoryService $historyService)
    {
        $this->key = 'history';
        $this->helper = $appHelper;
        $this->service = $historyService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(History|null $history): JsonResponse
    {
        if (!$history) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($history, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, History|null $history): JsonResponse
    {
        if (!$history) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $history);

        return new JsonResponse(200);
    }
}
