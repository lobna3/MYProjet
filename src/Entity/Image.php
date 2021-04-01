<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

   
    /**
     * @ORM\OneToOne(targetEntity=Pin::class, mappedBy="image", cascade={"persist", "remove"})
     */
    private $Pin;

    
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

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

   

    public function getPin(): ?Pin
    {
        return $this->Pin;
    }

    public function setPin(?Pin $Pin): self
    {
        // unset the owning side of the relation if necessary
        if ($Pin === null && $this->Pin !== null) {
            $this->Pin->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($Pin !== null && $Pin->getImage() !== $this) {
            $Pin->setImage($this);
        }

        $this->Pin = $Pin;

        return $this;
    }

  
}
