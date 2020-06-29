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
     * @var float|null
     */
    private $lat;

    /**
     * @var float|null
     */
    private $lng;

    /**
     * @var integer|null
     */
    private $distance;


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

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float|null $lat
     * @return AdSearch
     */
    public function setLat(?float $lat): AdSearch
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param float|null $lng
     * @return AdSearch
     */
    public function setLng(?float $lng): AdSearch
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDistance(): ?int
    {
        return $this->distance;
    }

    /**
     * @param int|null $distance
     * @return AdSearch
     */
    public function setDistance(?int $distance): AdSearch
    {
        $this->distance = $distance;
        return $this;
    }




}
