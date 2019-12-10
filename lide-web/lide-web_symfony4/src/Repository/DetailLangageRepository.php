<?php

namespace App\Repository;

use App\Entity\DetailLangage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DetailLangage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailLangage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailLangage[]    findAll()
 * @method DetailLangage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailLangageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailLangage::class);
    }

    // /**
    //  * @return DetailLangage[] Returns an array of DetailLangage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DetailLangage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
