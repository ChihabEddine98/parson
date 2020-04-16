<?php

namespace App\Repository;

use App\Entity\UserCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
      * @return UserCourse[] Returns an array of UserCourse objects
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
