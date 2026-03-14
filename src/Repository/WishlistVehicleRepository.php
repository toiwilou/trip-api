<?php

namespace App\Repository;

use App\Entity\WishlistVehicle;
use App\Traits\RepositoryTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<WishlistVehicle>
 */
class WishlistVehicleRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishlistVehicle::class);
    }

    public function getAll(): array
    {
        return $this->getActiveItems($this);
    }
}
