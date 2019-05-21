<?php

namespace App\Repository;

use App\Entity\ReponseEleveAssociation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReponseEleveAssociation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseEleveAssociation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseEleveAssociation[]    findAll()
 * @method ReponseEleveAssociation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseEleveAssociationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReponseEleveAssociation::class);
    }

    // /**
    //  * @return ReponseEleveAssociation[] Returns an array of ReponseEleveAssociation objects
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
    public function findOneBySomeField($value): ?ReponseEleveAssociation
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
