<?php

namespace App\Service;

use DateTime;
use App\Entity\History;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HistoryService
{
    private $repository;
    private $userService;
    private $entityManager;

    public function __construct(
        UserService $userService,
        HistoryRepository $historyRepository,
        EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->repository = $historyRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, History $history): void
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->getById($data['user']);
        $history
            ->setUser($user ?? $history->getUser())
            ->setDate($data['date'] ? new DateTime($data['date']) : $history->getDate())
            ->setAction($data['action'] ?? $history->getAction())
            ->setActive($data['active'] == 'true' ?? $history->isActive())
        ;

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new History());
    }

    public function edit(Request $request, History $history): void
    {
        $this->persist($request, $history);
    }
}
