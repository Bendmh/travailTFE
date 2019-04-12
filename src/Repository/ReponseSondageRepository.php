<?php

namespace App\Repository;

use App\Entity\ReponseSondage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReponseSondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseSondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseSondage[]    findAll()
 * @method ReponseSondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseSondageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReponseSondage::class);
    }

    public function resultSondage($id){
        return $this->createQueryBuilder('s')
                    ->select('s.response, count(s)')
                    ->andWhere('s.questionSondage = :id')
                    ->setParameter('id', $id)
                    ->groupBy('s.response')
                    ->getQuery()
                    ->getResult();
    }

    public function returnCount($id){
        return $this->createQueryBuilder('s')
                    ->select('count(s)')
                    ->andWhere('s.questionSondage = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult();
    }
    // /**
    //  * @return ReponseSondage[] Returns an array of ReponseSondage objects
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
    public function findOneBySomeField($value): ?ReponseSondage
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
