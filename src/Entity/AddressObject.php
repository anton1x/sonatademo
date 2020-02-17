<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressObjectRepository")
 * @UniqueEntity(fields={"title"})
 */
class AddressObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     * @JMS\Groups(groups={"calculator"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Groups(groups={"calculator"})
     */
    private $title;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TVPlan", mappedBy="assignedAddressObjects")
     * @JMS\Type("object_ids")
     */
    private $tvPlans;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConnectionType", inversedBy="addresses")
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $connectionType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PricingType", inversedBy="addresses")
     * @JMS\Exclude()
     */
    private $pricingType;



    public function __construct()
    {
        $this->tvPlans = new ArrayCollection();
        $this->connectionType = new ConnectionType();
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

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return InternetPlan[]|Collection
     * @JMS\VirtualProperty(name="internet_plans")
     * @JMS\Type("object_ids")
     */
    public function getInternetPlans()
    {
        return $this->getPricingType()->getInternetPlans();
    }

    /**
     * @return Collection|TVPlan[]
     */
    public function getTvPlans(): Collection
    {
        return $this->tvPlans;
    }

    public function addTvPlan(TVPlan $tvPlan): self
    {
        if (!$this->tvPlans->contains($tvPlan)) {
            $this->tvPlans[] = $tvPlan;
        }

        return $this;
    }

    public function removeTvPlan(TVPlan $tvPlan): self
    {
        if ($this->tvPlans->contains($tvPlan)) {
            $this->tvPlans->removeElement($tvPlan);
        }

        return $this;
    }

    public function getConnectionType(): ?ConnectionType
    {
        return $this->connectionType;
    }

    public function setConnectionType(?ConnectionType $connectionType): self
    {
        $this->connectionType = $connectionType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function getPricingType(): ?PricingType
    {
        return $this->pricingType;
    }

    public function setPricingType(?PricingType $pricingType): self
    {
        $this->pricingType = $pricingType;

        return $this;
    }




}
