<?php

namespace App\Repository;

use App\Entity\Apartment;
use App\Traits\RepositoryTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Apartment>
 */
class ApartmentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apartment::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }

    public function getApartmentsByCity(int $city): array
    {
        return $this->getByCity($this, $city);
    }
}
