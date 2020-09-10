<?php

namespace App\Entity;



use Doctrine\Common\Collections\ArrayCollection;

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
     * @var string|null
     */
    private $orderby;

    /**
     * @var ArrayCollection|null
     */
    private $options;

    /**
     * @var string|null
     */
    private $myadress;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }


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

    /**
     * @return ArrayCollection|null
     */
    public function getOptions(): ?ArrayCollection
    {
        return $this->options;
    }

    /**
     * @param ArrayCollection|null $options
     * @return AdSearch
     */
    public function setOptions(?ArrayCollection $options): AdSearch
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderby(): ?string
    {
        return $this->orderby;
    }

    /**
     * @param string|null $orderby
     * @return AdSearch
     */
    public function setOrderby(?string $orderby): AdSearch
    {
        $this->orderby = $orderby;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMyadress(): ?string
    {
        return $this->myadress;
    }

    /**
     * @param string|null $myadress
     * @return AdSearch
     */
    public function setMyadress(?string $myadress): AdSearch
    {
        $this->myadress = $myadress;
        return $this;
    }








}
