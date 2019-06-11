<?php

namespace App\Repository;

use App\Entity\Brainstorming;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Brainstorming|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brainstorming|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brainstorming[]    findAll()
 * @method Brainstorming[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrainstormingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Brainstorming::class);
    }

    // /**
    //  * @return Brainstorming[] Returns an array of Brainstorming objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Brainstorming
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
