<?php 

namespace App\Service;

use App\Entity\WishlistVehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class WishlistVehicleService
{
    private $entityManager;
    private $vehicleService;
    private $customerService;

    public function __construct(
        VehicleService $vehicleService,
        CustomerService $customerService,
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->vehicleService = $vehicleService;
        $this->customerService = $customerService;
    }

    public function persist(Request $request, WishlistVehicle $wishlist): void
    {
        $data = json_decode($request->getContent(), true);
        $vehicle = $this->vehicleService->getById((int) $data['vehicle']);
        $customer = $this->customerService->getById((int) $data['customer']);
        $wishlist
            ->setCustomer($customer ?? $wishlist->getCustomer())
            ->setVehicle($vehicle ?? $wishlist->getVehicle())
            ->setActive($data['active'] == 'true' ?? $wishlist->isActive())
        ;

        $this->entityManager->persist($wishlist);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new WishlistVehicle());
    }

    public function edit(Request $request, WishlistVehicle $wishlist): void
    {
        $this->persist($request, $wishlist);
    }
}
