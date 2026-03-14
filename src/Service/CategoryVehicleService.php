<?php

namespace App\Service;

use App\Entity\CategoryVehicle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryVehicleRepository;
use Symfony\Component\HttpFoundation\Request;

class CategoryVehicleService
{
    private $repository;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryVehicleRepository $categoryApartmentRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $categoryApartmentRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function persist(Request $request, CategoryVehicle $category): void
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
        $this->persist($request, new CategoryVehicle());
    }

    public function edit(Request $request, CategoryVehicle $category): void
    {
        $this->persist($request, $category);
    }

    public function createAll(): void
    {
        $datas = json_decode(file_get_contents(__DIR__ . '/../../jsons/vehicle_categories.json'), true);
        
        foreach ($datas as $data) {
            $category = $this->repository->findOneBy(['name' => $data['name']]);

            if (!$category)
            {
                $category = new CategoryVehicle();
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
