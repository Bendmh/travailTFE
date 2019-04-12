<?php

namespace App\Repository;

use App\Entity\QuestionsReponses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuestionsReponses|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionsReponses|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionsReponses[]    findAll()
 * @method QuestionsReponses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsReponsesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuestionsReponses::class);
    }

    // /**
    //  * @return QuestionsReponses[] Returns an array of QuestionsReponses objects
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

    public function findAnswerByActivity($id){
        return $this->createQueryBuilder('r')
                ->join('r.question', 'q')
                ->join('q.activity', 'a')
                ->andWhere('a.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?QuestionsReponses
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
