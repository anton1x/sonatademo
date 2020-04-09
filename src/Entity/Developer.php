<?php

namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeveloperRepository")
 */
class Developer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $apartmentsCount=0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $builder = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"all"}, fetch="EAGER")
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition()
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\SortableGroup
     */
    private $isPlannedProject = 0;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getApartmentsCount(): ?int
    {
        return $this->apartmentsCount;
    }

    public function setApartmentsCount(int $apartmentsCount): self
    {
        $this->apartmentsCount = $apartmentsCount;

        return $this;
    }

    public function getBuilder(): ?string
    {
        return $this->builder;
    }

    public function setBuilder(string $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getIsPlannedProject(): ?bool
    {
        return $this->isPlannedProject;
    }

    public function setIsPlannedProject(bool $isPlannedProject): self
    {
        $this->isPlannedProject = $isPlannedProject;

        return $this;
    }

//    /**
//     * @ORM\PostPersist()
//     */
//    public function postPersist()
//    {
//        $this->setPosition($this->getId());
//    }
}
