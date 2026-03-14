<?php

namespace App\Service;

use DateTime;
use App\Entity\Notice;
use App\Repository\NoticeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class NoticeService
{
    private $repository;
    private $entityManager;
    private $customerService;

    public function __construct(
        CustomerService $customerService,
        NoticeRepository $noticeRepository,
        EntityManagerInterface $entityManager)
    {
        $this->repository = $noticeRepository;
        $this->entityManager = $entityManager;
        $this->customerService = $customerService;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, Notice $notice): void
    {
        $data = json_decode($request->getContent(), true);
        $customer = $this->customerService->getById($data['customer']);
        $notice
            ->setStars($data['stars'] ? (int) $data['stars'] : $notice->getStars())
            ->setContent($data['content'] ?? $notice->getContent())
            ->setCustomer($customer ?? $notice->getCustomer())
            //->setPictures()
            //->setVideos()
            ->setDate($data['date'] ? new DateTime($data['date']) : $notice->getDate())
            ->setActive($data['active'] == 'true' ?? $notice->isActive())
        ;

        $this->entityManager->persist($notice);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Notice());
    }

    public function edit(Request $request, Notice $notice): void
    {
        $this->persist($request, $notice);
    }
}
