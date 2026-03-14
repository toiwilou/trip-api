<?php 

namespace App\Service;

use App\Entity\WishlistApartment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class WishlistApartmentService
{
    private $entityManager;
    private $customerService;
    private $apartmentService;

    public function __construct(
        CustomerService $customerService,
        ApartmentService $apartmentService,
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->customerService = $customerService;
        $this->apartmentService = $apartmentService;
    }

    public function persist(Request $request, WishlistApartment $wishlist): void
    {
        $data = json_decode($request->getContent(), true);
        $customer = $this->customerService->getById((int) $data['customer']);
        $apartment = $this->apartmentService->getById((int) $data['apartment']);
        $wishlist
            ->setCustomer($customer ?? $wishlist->getCustomer())
            ->setApartment($apartment ?? $wishlist->getApartment())
            ->setActive($data['active'] == 'true' ?? $wishlist->isActive())
        ;

        $this->entityManager->persist($wishlist);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $this->persist($request, new WishlistApartment());
    }

    public function edit(Request $request, WishlistApartment $wishlist): void
    {
        $this->persist($request, $wishlist);
    }
}
