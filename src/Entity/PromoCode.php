<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromoCodeRepository")
 */
class PromoCode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("promo")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("promo")
     */
    private $code;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("promo")
     */
    private $expiredAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("promo")
     */
    private $maxNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("promo")
     */
    private $type;


    /**
     * @ORM\Column(type="integer")
     * @Groups("promo")
     */
    private $amount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="promoCode")
     */
    private $booking;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="promoCodes")
     */
    private $user;

    public function __construct()
    {
        $this->booking = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getMaxNumber(): ?int
    {
        return $this->maxNumber;
    }

    public function setMaxNumber(?int $maxNumber): self
    {
        $this->maxNumber = $maxNumber;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBooking(): Collection
    {
        return $this->booking;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->booking->contains($booking)) {
            $this->booking[] = $booking;
            $booking->setPromoCode($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->booking->contains($booking)) {
            $this->booking->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getPromoCode() === $this) {
                $booking->setPromoCode(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
