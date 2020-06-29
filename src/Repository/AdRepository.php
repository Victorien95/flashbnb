<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\AdSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    public function findBestAds($limit)
    {
        return $this->createQueryBuilder('a')
                    ->select('a as annonce, AVG(c.rating) as avgRating')
                    ->join('a.comments', 'c')
                    ->groupBy('a')
                    ->orderBy('avgRating', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }



    public function maxRooms()
    {
        return $this->createQueryBuilder('a')
                    ->select('MAX(a.rooms)')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param AdSearch $search
     * @return Query
     */
    public function findAllVisibleQuery(AdSearch $search):Query
    {
        /** $query = $this->createQueryBuilder('a')
                      ->select('a')
                      ->orderBy('a.price');**/

        $query = $this->findVisibleQuery();

        if ($search->getMaxPrice() != null){
            $query = $query
                ->andWhere('a.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        if ($search->getMinRooms() != null){
            $query = $query
                ->andWhere('a.rooms >= :minrooms')
                ->setParameter('minrooms', $search->getMinRooms());
        }
        if($search->getLat() && $search->getLng() && $search->getDistance()){
            $query = $query
                ->andWhere('(6353 * 2 * ASIN(SQRT(POWER(SIN((a.lat - :lat) * pi()/180 / 2), 2) +COS(a.lat 
                * pi() / 180) * COS(:lat * pi()/180) * POWER(SIN((a.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
                ->setParameter('lng', $search->getLng())
                ->setParameter('lat', $search->getLat())
                ->setParameter('distance', $search->getDistance());
        }
        if ($search->getOptions()->count() > 0){
            $k = 1;
            foreach ($search->getOptions() as $option){
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF a.options")
                    ->setParameter("option$k", $option);
            }
        }

        return $query->getQuery();
    }

    private function findVisibleQuery()
    {
        return $this->createQueryBuilder('a');
    }





    // /**
    //  * @return Ad[] Returns an array of Ad objects
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
    public function findOneBySomeField($value): ?Ad
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
