<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PricingTypeRepository")
 */
class PricingType
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
     * @ORM\OneToMany(targetEntity="App\Entity\AddressObject", mappedBy="pricingType")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InternetPlan", mappedBy="pricingType")
     */
    private $internetPlans;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->internetPlans = new ArrayCollection();
    }


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

    /**
     * @return Collection|AddressObject[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(AddressObject $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setPricingType($this);
        }

        return $this;
    }

    public function removeAddress(AddressObject $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getPricingType() === $this) {
                $address->setPricingType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InternetPlan[]
     */
    public function getInternetPlans(): Collection
    {
        return $this->internetPlans;
    }

    public function addInternetPlan(InternetPlan $internetPlan): self
    {
        if (!$this->internetPlans->contains($internetPlan)) {
            $this->internetPlans[] = $internetPlan;
            $internetPlan->setPricingType($this);
        }

        return $this;
    }

    public function removeInternetPlan(InternetPlan $internetPlan): self
    {
        if ($this->internetPlans->contains($internetPlan)) {
            $this->internetPlans->removeElement($internetPlan);
            // set the owning side to null (unless already changed)
            if ($internetPlan->getPricingType() === $this) {
                $internetPlan->setPricingType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
