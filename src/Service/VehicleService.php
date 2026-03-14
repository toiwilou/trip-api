<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Vehicle;
use App\Repository\CityRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class VehicleService
{
    private $repository;
    private $entityManager;
    private $cityRepository;
    private $categoryVehicleService;

    public function __construct(
        CityRepository $cityRepository,
        VehicleRepository $vehicleRepository,
        EntityManagerInterface $entityManager,
        CategoryVehicleService $categoryVehicleService)
    {
        $this->entityManager = $entityManager;
        $this->repository = $vehicleRepository;
        $this->cityRepository = $cityRepository;
        $this->categoryVehicleService = $categoryVehicleService;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): Vehicle
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function getVehiclesByCity(int $city): array
    {
        return $this->repository->getVehiclesByCity($city);
    }

    public function persistDatas(array $data, Vehicle $vehicle): void
    {
        $postalCode = $data['postal_code'];
        $city = $this->cityRepository->findOneBy(['postal_code' => $postalCode]);
        $category = $this->categoryVehicleService->getById((int) $data['category']);
        
        if (!$city) {
            $city = new City();
            $city
                ->setName($data['city'])
                ->setPostalCode($postalCode)
                ->setCountry($data['country'])
                ->setActive(true)
            ;
        }

        $vehicle
            ->setCategory($category ?? $vehicle->getCategory())
            ->setBrand($data['brand'] ?? $vehicle->getBrand())
            ->setModel($data['model'] ?? $vehicle->getModel())
            ->setAddress($data['address'] ?? $vehicle->getAddress())
            ->setCity($city)
            ->setYear($data['year'] ? (int) $data['year'] : $vehicle->getYear())
            ->setDescription($data['description'] ?? $vehicle->getDescription())
            ->setColor($data['color'] ?? $vehicle->getColor())
            ->setMileage($data['mileage'] ?? $vehicle->getMileage())
            ->setPrice($data['price'] ? floatval($data['price']) : $vehicle->getPrice())
            ->setAvailable($data['available'] == 'true' ?? $vehicle->isAvailable())
            ->setPrincipalPicture($data['principal_picture'] ?? $vehicle->getPrincipalPicture())
            //->setPictures()
            //->setVideos()
            ->setActive($data['active'] == 'true' ?? $vehicle->isActive())
        ;

        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();
    }

    public function persist(Request $request, Vehicle $vehicle): void
    {
        $data = json_decode($request->getContent(), true);
        
        $this->persistDatas($data, $vehicle);
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Vehicle());
    }

    public function edit(Request $request, Vehicle $vehicle): void
    {
        $this->persist($request, $vehicle);
    }
}
