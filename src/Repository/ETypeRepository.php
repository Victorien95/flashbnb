<?php

namespace App\Repository;

use App\Entity\EType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EType[]    findAll()
 * @method EType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ETypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EType::class);
    }

    // /**
    //  * @return EType[] Returns an array of EType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EType
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
