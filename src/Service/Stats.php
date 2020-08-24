<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;

class Stats
{
    private $manager;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount()
    {
        return $this->manager->createQuery('SELECT count(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getBookingsCount()
    {
        return $this->manager->createQuery('SELECT count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT count(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getNewsletterCount()
    {
        return $this->manager->createQuery('SELECT count(n) FROM App\Entity\Newsletter n')->getSingleScalarResult();
    }

    public function getAdsStats($direction)
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.avatarname, u.picture, u.id as userId
                 FROM APP\Entity\Comment AS c
                 JOIN c.ad AS a
                 JOIN a.author AS u
                 GROUP BY a
                 ORDER BY note ' . $direction
        )->setMaxResults(5)->getResult();
    }

    public function getMaxRooms()
    {
        return $this->manager->createQuery('SELECT MAX(a.rooms) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

}