<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isBig = false;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="city")
     */
    private $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return City
     */
    public function setTitle(string $title): City
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return City
     */
    public function setDescription(string $description): City
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBig(): ?bool
    {
        return $this->isBig;
    }

    /**
     * @param bool $isBig
     * @return City
     */
    public function setIsBig(bool $isBig): City
    {
        $this->isBig = $isBig;
        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}