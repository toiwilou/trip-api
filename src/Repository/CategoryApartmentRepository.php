<?php

namespace App\Repository;

use App\Traits\RepositoryTrait;
use App\Entity\CategoryApartment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CategoryApartment>
 */
class CategoryApartmentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryApartment::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }
}
