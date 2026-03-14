<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomerService
{
    private $repository;
    private $userService;
    private $entityManager;

    public function __construct(
        UserService $userService,
        EntityManagerInterface $entityManager,
        CustomerRepository $customerRepository)
    {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->repository = $customerRepository;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): Customer
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function persist(Request $request, Customer $customer): void
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->persistCustomerUser($request, $customer->getUser());
        $customer
            ->setUser($user ?? $customer->getUser())
            ->setPhone($data['phone'] ?? $customer->getPhone())
        ;

        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    public function add(Request $request): void
    {
        $customer = new Customer();
        $customer->setUser(new User());

        $this->persist($request, $customer);
    }

    public function edit(Request $request, Customer $customer): void
    {
        $this->persist($request, $customer);
    }
}
