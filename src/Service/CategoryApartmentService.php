<?php

namespace App\Service;

use App\Entity\CategoryApartment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryApartmentRepository;

class CategoryApartmentService
{
    private $repository;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryApartmentRepository $categoryApartmentRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $categoryApartmentRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): CategoryApartment
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function persist(Request $request, CategoryApartment $category): void
    {
        $data = json_decode($request->getContent(), true);
        $category
            ->setName($data['name'] ?? $category->getName())
            ->setAdvantage($data['advantage'] ?? $category->getAdvantage())
            ->setActive($data['active'] == 'true' ?? $category->isActive())
        ;

        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new CategoryApartment());
    }

    public function edit(Request $request, CategoryApartment $category): void
    {
        $this->persist($request, $category);
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../jsons/apartment_categories.json'), true);
        
        foreach ($datas as $data) {
            $category = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$category)
            {
                $category = new CategoryApartment();
                $category
                    ->setActive(true)
                    ->setName($data['name'])
                    ->setAdvantage($data['advantage'])
                ;

                $this->entityManager->persist($category);
                $this->entityManager->flush();
            }
        }
    }
}
