<?php

namespace App\Entity;



class AdSearch
{

    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var int|null
     */
    private $minRooms;


    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     * @return AdSearch
     */
    public function setMaxPrice(int $maxPrice): AdSearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinRooms(): ?int
    {
        return $this->minRooms;
    }

    /**
     * @param int|null $minRooms
     * @return AdSearch
     */
    public function setMinRooms(int $minRooms): AdSearch
    {
        $this->minRooms = $minRooms;
        return $this;
    }


}
