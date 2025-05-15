<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // src/Repository/EventRepository.php

    public function findByFilter(array $criteria): array
    {
        $qb = $this->createQueryBuilder('e');
    
        if (!empty($criteria['name'])) {
            $qb->andWhere('e.name LIKE :name')
               ->setParameter('name', '%' . $criteria['name'] . '%');
        }
    
        if (!empty($criteria['location'])) {
            $qb->andWhere('e.location LIKE :location')
               ->setParameter('location', '%' . $criteria['location'] . '%');
        }
    
        if (!empty($criteria['minPrice'])) {
            $qb->andWhere('e.price >= :minPrice')
               ->setParameter('minPrice', $criteria['minPrice']);
        }
    
        if (!empty($criteria['maxPrice'])) {
            $qb->andWhere('e.price <= :maxPrice')
               ->setParameter('maxPrice', $criteria['maxPrice']);
        }


        if (!empty($criteria['startDate'])) {
            $qb->andWhere('e.date >= :startDate')
               ->setParameter('startDate', $criteria['startDate']);
        }
    
        if (!empty($criteria['endDate'])) {
            $qb->andWhere('e.date <= :endDate')
               ->setParameter('endDate', $criteria['endDate']);
        }
    
        return $qb->getQuery()->getResult();
    }
}  