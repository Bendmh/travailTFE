<?php

namespace App\Repository;

use App\Entity\ReponseEleveQCM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReponseEleveQCM|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseEleveQCM|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseEleveQCM[]    findAll()
 * @method ReponseEleveQCM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseEleveQCMRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReponseEleveQCM::class);
    }

    // /**
    //  * @return ReponseEleveQCM[] Returns an array of ReponseEleveQCM objects
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
    public function findOneBySomeField($value): ?ReponseEleveQCM
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
