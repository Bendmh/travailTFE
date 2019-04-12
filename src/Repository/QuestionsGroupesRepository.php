<?php

namespace App\Repository;

use App\Entity\QuestionsGroupes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuestionsGroupes|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionsGroupes|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionsGroupes[]    findAll()
 * @method QuestionsGroupes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsGroupesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuestionsGroupes::class);
    }

    // /**
    //  * @return QuestionsGroupes[] Returns an array of QuestionsGroupes objects
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
    public function findOneBySomeField($value): ?QuestionsGroupes
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
