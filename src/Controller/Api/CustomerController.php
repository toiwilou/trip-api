<?php

namespace App\Controller\Api;

use App\Entity\Customer;
use App\Helper\AppHelper;
use App\Service\CustomerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/customers')]
final class CustomerController extends AbstractController
{
    private $key;
    private $helper;
    private $service;

    public function __construct(
        AppHelper $appHelper,
        CustomerService $customerService)
    {
        $this->key = 'customer';
        $this->helper = $appHelper;
        $this->service = $customerService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->helper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Customer|null $customer): JsonResponse
    {
        if (!$customer) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->helper->serialize($customer, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Customer|null $customer): JsonResponse
    {
        if (!$customer) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $customer);

        return new JsonResponse(200);
    }
}
