<?php

namespace App\Repository;

use App\Entity\ActivitySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActivitySearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivitySearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivitySearch[]    findAll()
 * @method ActivitySearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivitySearchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActivitySearch::class);
    }

    // /**
    //  * @return ActivitySearch[] Returns an array of ActivitySearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActivitySearch
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
