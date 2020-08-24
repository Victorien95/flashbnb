<?php

namespace App\Entity;

use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @Vich\Uploadable
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var TextType|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var File|null
     * @Assert\Image(mimeTypes={"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage="Le format de votre fichier est invalide ({{ type }}). Formats acceptés: {{ types }}")
     * @Vich\UploadableField(mapping="ad_image", fileNameProperty="caption")
     *
     */
    private $imageFile;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="10", minMessage="Le titre de l'image doit faire au moins 10 caractères")
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ad", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getAd(): ?ad
    {
        return $this->ad;
    }

    public function setAd(?ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Image
     */
    public function setImageFile(?File $imageFile): Image
    {
        $this->imageFile = $imageFile;

        return $this;
    }


}
