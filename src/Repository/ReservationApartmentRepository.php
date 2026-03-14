<?php

namespace App\Repository;

use App\Traits\RepositoryTrait;
use App\Entity\ReservationApartment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ReservationApartment>
 */
class ReservationApartmentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationApartment::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }
}
