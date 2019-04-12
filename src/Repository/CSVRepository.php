<?php

namespace App\Repository;

use App\Entity\CSV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CSV|null find($id, $lockMode = null, $lockVersion = null)
 * @method CSV|null findOneBy(array $criteria, array $orderBy = null)
 * @method CSV[]    findAll()
 * @method CSV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CSVRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CSV::class);
    }

    // /**
    //  * @return CSV[] Returns an array of CSV objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CSV
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
