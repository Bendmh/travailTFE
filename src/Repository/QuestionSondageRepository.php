<?php

namespace App\Repository;

use App\Entity\QuestionSondage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuestionSondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionSondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionSondage[]    findAll()
 * @method QuestionSondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionSondageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuestionSondage::class);
    }

    // /**
    //  * @return QuestionSondage[] Returns an array of QuestionSondage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionSondage
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
