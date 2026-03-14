<?php 

namespace App\Traits;

trait RepositoryTrait
{
    public function getAliasByRepository($queryBuilder, $repository): string
    {
        $alias = 'e'; // e as entity
        $path = 'App\Repository\\';
        $repositories = [
            [ 'path' => $path . 'CustomerRepository', 'join' => 'user' ],
            [ 'path' => $path . 'ReservationVehicleRepository', 'join' => 'reservation' ],
            [ 'path' => $path . 'ReservationApartmentRepository', 'join' => 'reservation' ]
        ];

        foreach ($repositories as $item) {
            if ($repository == $item['path']) {
                $queryBuilder = $queryBuilder->join('e.' . $item['join'], 'j');
                $alias = 'j'; // j as join
            }
        }

        return $alias;
    }

    public function getActiveItems($repository): array
    {
        $queryBuilder = $repository->createQueryBuilder('e');
        $alias = $this->getAliasByRepository($queryBuilder, get_class($repository));
        
        return $queryBuilder
            ->andWhere($alias . '.active = :value')
            ->setParameter('value', true)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getByCity($repository, int $city): array
    {
        return $repository->createQueryBuilder('e')
            ->andWhere('e.city = :city')
            ->andWhere('e.active = :value')
            ->setParameter('city', $city)
            ->setParameter('value', true)
            ->getQuery()
            ->getResult()
        ;
    }
}
