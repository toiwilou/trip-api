<?php

namespace App\Repository;

use App\Entity\CategoryVehicle;
use App\Traits\RepositoryTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CategoryVehicle>
 */
class CategoryVehicleRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryVehicle::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }
}
