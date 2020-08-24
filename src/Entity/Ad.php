<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;




/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"title", "slug"},
 *     message="Une autre annonce possède déjà ce titre. Merci de le modifier")
 * @Vich\Uploadable
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File|null
     * @Assert\Image(mimeTypes={"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage="Le format de votre fichier est invalide ({{ type }}). Formats acceptés: {{ types }}")
     * @Vich\UploadableField(mapping="ad_cover_image", fileNameProperty="adCoverImage")
     */
    private $imageFile2;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adCoverImage;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="10", max="255",
     *     minMessage="Le titre doit faire plus de 10 caractères",
     *     maxMessage="Le tite ne peut pas faire plus de 50 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Le prix ne peut pas être négatif")
     * @Assert\GreaterThanOrEqual(value="50" ,message="Le prix ne peut pas être inférieur à 50€ par nuits")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min="10", minMessage="Votre introduction ne peut pas faire moin de 20 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min="100", minMessage="Votre description détaillée ne peut pas faire moin de 100 caractères")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Le nombre de chambre ne peut pas être négatif")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private $images;

    /**
     * @Assert\All({
     *     @Assert\Image(mimeTypes={"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage="Le format de votre fichier est invalide ({{ type }}). Formats acceptés: {{ types }}")
     * })
     */
    private $imageFiles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="ad", cascade={"remove"})
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="ad", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postal_code;

    /**
     * @ORM\Column(type="float", scale=4, precision=6)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", scale=4, precision=7)
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street_address;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="ads")
     */
    private $options;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="ad", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->y = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug avec cycle de vie
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug()
    {
        if (empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    /**
     * Permet de récupérer le commentaire d'un auteur par rapport à une annonce
     *
     * @param User $author
     * @return mixed|null
     */
    public function getCommentFromAuthor(User $author)
    {
        foreach ($this->comments as $comment){
            if ($comment->getAuthor() === $author) return $comment;
        }
        return null;
    }

    /**
     * Permet d'obtenir la moyenne des notes pour cette annonce
     *
     * @return float|int
     */
    public function getAvgRatings()
    {
        // somme des notations
        $sum = array_reduce($this->comments->toArray(), function ($total, $comment){
            return $total + $comment->getRating();
        }, 0);

        // Division pour la moyenne
        if (count($this->comments) > 0) return $sum / count($this->comments);
        return 0;
    }
    
    /**
     * Permet d'obtenir un tableau des jours indisponibles pour cette annonce
     *
     * @return array
     */
    public function getNotAvailableDays()
    {
        $notAvailableDays = [];
        foreach ($this->bookings as $booking){
            $resultat = range(
                $booking->getStartDate()->getTimestamp(),
                $booking->getEndDate()->getTimestamp(),
                (24*60*60)
            );
            $days = array_map(function ($dayTimestamp){
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);

            $notAvailableDays = array_merge($notAvailableDays, $days);
        }
        return $notAvailableDays;
    }

    /**
     * Permet de savoir si un article est liké par un utilisateur
     * @param User $user
     * @return bool
     */
    public function isLikeByUser(User $user)
    {
        foreach ($this->likes as $like){
            if ($like->getUser() === $user ) return true;
        }
        return false;
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getStreetAddress(): ?string
    {
        return $this->street_address;
    }

    public function setStreetAddress(string $street_address): self
    {
        $this->street_address = $street_address;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addAd($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeAd($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFiles()
    {
        return $this->imageFiles;
    }

    /**
     * @param mixed $imageFiles
     * @return Ad
     */
    public function setImageFiles($imageFiles)
    {

        foreach ($imageFiles as $imageFile){
            $image = new Image();
            $image->setImageFile($imageFile);
            $this->addImage($image);
        }

        $this->imageFiles = $imageFiles;
        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setAd($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getAd() === $this) {
                $like->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    /**
     * @param File|null $imageFile2
     * @return Ad
     */
    public function setImageFile2(?File $imageFile2): Ad
    {
        $this->imageFile2 = $imageFile2;
        if ($this->imageFile2 instanceof UploadedFile){
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdCoverImage(): ?string
    {
        return $this->adCoverImage;
    }

    /**
     * @param string|null $adCoverImage
     * @return Ad
     */
    public function setAdCoverImage(?string $adCoverImage): Ad
    {
        $this->adCoverImage = $adCoverImage;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }





}
