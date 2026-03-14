<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Apartment;
use App\Repository\CityRepository;
use App\Repository\ApartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApartmentService
{
    private $repository;
    private $entityManager;
    private $cityRepository;
    private $categoryApartmentService;

    public function __construct(
        CityRepository $cityRepository,
        EntityManagerInterface $entityManager,
        ApartmentRepository $apartmentRepository,
        CategoryApartmentService $categoryApartmentService)
    {
        $this->entityManager = $entityManager;
        $this->cityRepository = $cityRepository;
        $this->repository = $apartmentRepository;
        $this->categoryApartmentService = $categoryApartmentService;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): Apartment
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function getApartmentsByCity(int $city): array
    {
        return $this->repository->getApartmentsByCity($city);
    }

    public function persistDatas(array $data, Apartment $apartment): void
    {
        $postalCode = $data['postal_code'];
        $city = $this->cityRepository->findOneBy(['postal_code' => $postalCode]);
        $category = $this->categoryApartmentService->getById((int) $data['category']);
        
        if (!$city) {
            $city = new City();
            $city
                ->setName($data['city'])
                ->setPostalCode($postalCode)
                ->setCountry($data['country'])
                ->setActive(true)
            ;
        }

        $apartment
            ->setCategory($category ?? $apartment->getCategory())
            ->setName($data['name'] ?? $apartment->getName())
            ->setAddress($data['address'] ?? $apartment->getAddress())
            ->setCity($data['city'] ?? $apartment->getCity())
            ->setDescription($data['description'] ?? $apartment->getDescription())
            ->setRooms($data['rooms'] ? (int) $data['rooms'] : $apartment->getRooms())
            ->setBathRooms($data['bath_rooms'] ? (int) $data['bath_rooms'] : $apartment->getBathRooms())
            ->setArea($data['area'] ? (int) $data['area'] : $apartment->getArea())
            ->setPrice($data['price'] ? floatval($data['price']) : $apartment->getPrice())
            ->setOwner($data['owner'] ?? $apartment->getOwner())
            ->setAvailable($data['available'] ? $data['available'] == 'true' : $apartment->isAvailable())
            ->setPrincipalPicture($data['principal_picture'] ?? $apartment->getPrincipalPicture())
            //->setPictures()
            //->setVideos()
            ->setAvailable($data['active'] == 'true' ?? $apartment->isActive())
        ;

        $this->entityManager->persist($apartment);
        $this->entityManager->persist($city);
        $this->entityManager->flush();
    }

    public function persist(Request $request, Apartment $apartment): void
    {
        $data = json_decode($request->getContent(), true);
        
        $this->persistDatas($data, $apartment);
    }

    public function add(Request $request): void
    {
        $this->persist($request, new Apartment());
    }

    public function edit(Request $request, Apartment $apartment): void
    {
        $this->persist($request, $apartment);
    }
}
