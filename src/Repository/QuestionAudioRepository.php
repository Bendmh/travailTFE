<?php

namespace App\Repository;

use App\Entity\QuestionAudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuestionAudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionAudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionAudio[]    findAll()
 * @method QuestionAudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionAudioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuestionAudio::class);
    }

    // /**
    //  * @return QuestionAudio[] Returns an array of QuestionAudio objects
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
    public function findOneBySomeField($value): ?QuestionAudio
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
