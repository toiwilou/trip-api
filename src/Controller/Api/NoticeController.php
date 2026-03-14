<?php

namespace App\Controller\Api;

use App\Entity\Notice;
use App\Helper\AppHelper;
use App\Service\NoticeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/notices')]
final class NoticeController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        NoticeService $noticeService)
    {
        $this->key = 'notice';
        $this->helper = $appHelper;
        $this->service = $noticeService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Notice|null $notice): JsonResponse
    {
        if (!$notice) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($notice, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Notice|null $notice): JsonResponse
    {
        if (!$notice) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $notice);

        return new JsonResponse(200);
    }
}
