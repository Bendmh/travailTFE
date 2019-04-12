<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\ActivitySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findActivitySearch(ActivitySearch $search){

        $query = $this
                ->createQueryBuilder('p');

        if($search->getCreatorName()){
            $query = $query
                    ->andWhere('p.created_by = :creator')
                    ->setParameter('creator', $search->getCreatorName());
        }

        if($search->getActivityName()){
            $query = $query
                ->andWhere('p.id = :name')
                ->setParameter('name', $search->getActivityName());
        }

        return $query
            ->getquery()
            ->getResult();
    }

    public function giveAnswer($id){
        return $this
                ->createQueryBuilder('p')
                ->join('p.questionsGroupes', 'g')
                ->join('g.questionsReponses', 'r')
                ->select('r.name')
                ->andWhere('p.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
    }

    // /**
    //  * @return Activity[] Returns an array of Activity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
