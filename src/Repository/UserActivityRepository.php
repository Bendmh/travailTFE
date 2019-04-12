<?php

namespace App\Repository;

use App\Entity\ResultSearch;
use App\Entity\UserActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserActivity[]    findAll()
 * @method UserActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserActivityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserActivity::class);
    }

    public function myFindAll(){

        return $this
            ->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }

    public function myFind($id){

        $querybuilder = $this->createQueryBuilder('u');

        $querybuilder->where('u.user_id = :id')
                    ->setParameter('id', $id);

        $query = $querybuilder->getQuery();

        $result = $query->getResult();

        return $result;
    }

    public function findAllVisibleQuery(ResultSearch $search){

            $query = $this
                    ->createQueryBuilder('p')
                    ->join('p.user_id', 'r')
                    ->join('r.classes', 'q')
                    ->addSelect('r')
                    ->andWhere('r.titre = :titre')
                    ->setParameter('titre', 'ROLE_ELEVE')
                    ->addSelect('q');

            if($search->getClasse()){
                $query = $query
                    ->andWhere('q.nom = :classe')
                    ->setParameter('classe', $search->getClasse());
            }

            if($search->getMatiere()){
                $query = $query
                    ->andWhere('p.activity_id = :matiere')
                    ->setParameter('matiere', $search->getMatiere());
            }

        return $query
                    ->getquery()
                    ->getResult();


       /*if($search->getClasse()){
            $query = $query->where('p.userId = 17')
                            /*->setParameter('nom', $search->getClasse()->getNom());
       }

       return $query->getQuery();*/
    }

    // /**
    //  * @return UserActivity[] Returns an array of UserActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserActivity
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
