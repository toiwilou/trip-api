<?php

namespace App\Repository;

use App\Traits\RepositoryTrait;
use App\Entity\WishlistApartment;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<WishlistApartment>
 */
class WishlistApartmentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishlistApartment::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }
}
