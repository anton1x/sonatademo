<?php

namespace App\Entity\Sliders;

use App\Application\Sonata\MediaBundle\Entity\Gallery;
use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sliders\SlideGalleryRepository")
 */
class SlideGallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sliders\Slide", mappedBy="slideGallery", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $slides;

    /**
     * @ORM\Column("code", nullable=true, type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, inversedBy="slideGalleries")
     */
    private $additionalImages;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Gallery", cascade={"persist", "remove"})
     */
    private $additionalGallery;


    public function __construct()
    {
        $this->slides = new ArrayCollection();
        $this->additionalImages = new ArrayCollection();
        $this->additionalGallery = new Gallery();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Collection|Slide[]
     */
    public function getSlides(): Collection
    {
        return $this->slides;
    }

    public function addSlide(Slide $slide): self
    {
        if (!$this->slides->contains($slide)) {
            $this->slides[] = $slide;
            $slide->setSlideGallery($this);
        }

        return $this;
    }

    public function removeSlide(Slide $slide): self
    {
        if ($this->slides->contains($slide)) {
            $this->slides->removeElement($slide);
            // set the owning side to null (unless already changed)
            if ($slide->getSlideGallery() === $this) {
                $slide->setSlideGallery(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return Collection|Media[]
     */
    public function getAdditionalImages(): Collection
    {
        return $this->additionalImages;
    }

    public function addAdditionalImage(Media $additionalImage): self
    {
        if (!$this->additionalImages->contains($additionalImage)) {
            $this->additionalImages[] = $additionalImage;
        }

        return $this;
    }

    public function removeAdditionalImage(Media $additionalImage): self
    {
        if ($this->additionalImages->contains($additionalImage)) {
            $this->additionalImages->removeElement($additionalImage);
        }

        return $this;
    }

    /**
     * @return Gallery
     */
    public function getAdditionalGallery(): Gallery
    {
        if (null === $this->additionalGallery)
            $this->additionalGallery = new Gallery();
        return $this->additionalGallery;
    }

    /**
     * @param Gallery $additionalGallery
     */
    public function setAdditionalGallery(Gallery $additionalGallery): void
    {
        $this->additionalGallery = $additionalGallery;
    }





}
