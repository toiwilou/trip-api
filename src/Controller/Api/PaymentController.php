<?php

namespace App\Controller\Api;

use App\Entity\Payment;
use App\Helper\AppHelper;
use App\Service\PaymentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/payments')]
final class PaymentController extends AbstractController
{
    private $key;
    private $service;
    private $appHelper;

    public function __construct(
        AppHelper $appHelper,
        PaymentService $paymentService)
    {
        $this->key = 'payment';
        $this->appHelper = $appHelper;
        $this->service = $paymentService;
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $collection = $this->service->getAll();
        $data = $this->appHelper->serialize($collection, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Payment|null $payment): JsonResponse
    {
        if (!$payment) return new JsonResponse(['error' => 'Not found'], 404);

        $data = $this->appHelper->serialize($payment, $this->key);

        return new JsonResponse($data, 200, [], true);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->service->add($request);

        return new JsonResponse(201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Payment|null $payment): JsonResponse
    {
        if (!$payment) return new JsonResponse(['error' => 'Not found'], 404);

        $this->service->edit($request, $payment);

        return new JsonResponse(200);
    }
}
