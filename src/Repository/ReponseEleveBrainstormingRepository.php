<?php

namespace App\Repository;

use App\Entity\ReponseEleveBrainstorming;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReponseEleveBrainstorming|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseEleveBrainstorming|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseEleveBrainstorming[]    findAll()
 * @method ReponseEleveBrainstorming[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseEleveBrainstormingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReponseEleveBrainstorming::class);
    }

    // /**
    //  * @return ReponseEleveBrainstorming[] Returns an array of ReponseEleveBrainstorming objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReponseEleveBrainstorming
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
