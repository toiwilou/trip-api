<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Traits\RepositoryTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }

    public function getVehiclesByCity(int $city): array
    {
        return $this->getByCity($this, $city);
    }
}
