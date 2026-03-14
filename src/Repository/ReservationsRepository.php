<?php

namespace App\Repository;

use App\Entity\Reservations;
use App\Traits\RepositoryTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reservations>
 */
class ReservationsRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }
    
    public function getAll(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.reservation_apartment', 'ra', 'WITH', 'ra IS NOT NULL')
            ->leftJoin('r.reservation_vehicle', 'rv', 'WITH', 'rv IS NOT NULL')
            ->addSelect('ra', 'rv')
            ->leftJoin('ra.reservation', 'rar', 'WITH', 'rar IS NOT NULL')
            ->leftJoin('r.reservation', 'rvr', 'WITH', 'rvr IS NOT NULL')
            ->addSelect('rar', 'rvr')
            ->andWhere('rar.active = :value or rvr.active = :value')
            ->setParameter('value', true)
            ->getQuery()
            ->getResult()
        ;
    }
}
