<?php

namespace App\Service;

class AppBootService
{
    private $userService;
    private $statusService;
    private $categoryVehicleService;
    private $categoryApartmentService;

    public function __construct(
        UserService $userService,
        StatusService $statusService,
        CategoryVehicleService $categoryVehicleService,
        CategoryApartmentService $categoryApartmentService)
    {
        $this->userService = $userService;
        $this->statusService = $statusService;
        $this->categoryVehicleService = $categoryVehicleService;
        $this->categoryApartmentService = $categoryApartmentService;
    }

    public function boot(): void
    {
        $this->statusService->createAll();
        $this->userService->createFirstUser();
        $this->categoryVehicleService->createAll();
        $this->categoryApartmentService->createAll();
    }
}
