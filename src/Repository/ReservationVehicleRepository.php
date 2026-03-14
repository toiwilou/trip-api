<?php

namespace App\Repository;

use App\Traits\RepositoryTrait;
use App\Entity\ReservationVehicle;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ReservationVehicle>
 */
class ReservationVehicleRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationVehicle::class);
    }
    
    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }
}
