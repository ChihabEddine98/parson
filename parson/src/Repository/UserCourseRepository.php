<?php

namespace App\Repository;

use App\Entity\UserCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method UserCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCourse[]    findAll()
 * @method UserCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCourse::class);
    }

     /**
      * @return float Returns the average for score
      */

    public function findAverageByCourse($value)
    {
        $scoreDql =$this->createQueryBuilder('u')
            ->select('avg(u.score) as note_moyenne')
            ->Where('u.course = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();

        return $scoreDql[0]['note_moyenne'];
    }

    /**
     * @return float Returns the average for rating
     */

    public function findAverageRateByCourse($value)
    {
        $scoreDql =$this->createQueryBuilder('u')
            ->select('avg(u.rate) as rate_moyenne')
            ->Where('u.course = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();

        return $scoreDql[0]['rate_moyenne'];
    }
    /**
     * @return float Returns the average for rating
     */

    public function findAverageByUser($value)
    {
        $scoreDql =$this->createQueryBuilder('u')
            ->select('avg(u.score) as moyenne')
            ->Where('u.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();

        return $scoreDql[0]['moyenne'];
    }
    /**
     * @return float Returns the average for rating
     */

    public function findAverageRateByUser($value)
    {
        $scoreDql =$this->createQueryBuilder('u')
            ->select('avg(u.rate) as rate_moyenne')
            ->Where('u.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();

        return $scoreDql[0]['rate_moyenne'];
    }


    public function findOneByUserAndCourse($user,$course)
    {
           $result= $this->createQueryBuilder('u')
                ->andWhere('u.user = :val')
                ->setParameter('val', $user)
                ->andWhere('u.course = :c')
                ->setParameter('c', $course)
                ->getQuery()
                ->getResult();

           if (count($result)>1)
           {
               return $result[0];
           }
           return $result;

    }

    /*
    public function findOneBySomeField($value): ?UserCourse
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
