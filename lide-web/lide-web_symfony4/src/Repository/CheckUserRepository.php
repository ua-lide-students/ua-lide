<?php

namespace App\Repository;

use App\Entity\CheckUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CheckUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckUser[]    findAll()
 * @method CheckUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckUser::class);
    }

    // /**
    //  * @return CheckUser[] Returns an array of CheckUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CheckUser
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
