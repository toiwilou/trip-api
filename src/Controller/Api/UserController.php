<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Helper\AppHelper;
use App\Service\UserService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/users')]
final class UserController extends AbstractController
{
    private $key;
    private $service;
    private $appHelper;

    public function __construct(
        AppHelper $appHelper,
        UserService $userService)
    {
        $this->key = 'user';
        $this->service = $userService;
        $this->appHelper = $appHelper;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->appHelper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(User|null $user): JsonResponse
    {
        if (!$user) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->appHelper->serialize($user, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, User|null $user): JsonResponse
    {
        if (!$user) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $user);

        return new JsonResponse(200);
    }
}
