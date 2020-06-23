<?php

namespace App\Repository;

use App\Entity\AdSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdSearch[]    findAll()
 * @method AdSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdSearch::class);
    }

    // /**
    //  * @return AdSearch[] Returns an array of AdSearch objects
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
    public function findOneBySomeField($value): ?AdSearch
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
