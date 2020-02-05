<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InternetPlanRepository")
 */
class InternetPlan extends BaseProduct
{

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AddressObject", inversedBy="internetPlans")
     * @JMS\Type("object_ids")
     */
    private $assignedAddressObjects;

    /**
     * @ORM\Column(type="integer")
     */
    private $speed = 0;

    public function __construct()
    {
        parent::__construct();
        $this->assignedAddressObjects = new ArrayCollection();
    }

    public const type = 'internet_plan';

    /**
     * @return Collection|AddressObject[]
     */
    public function getAssignedAddressObjects(): Collection
    {
        return $this->assignedAddressObjects;
    }

    public function addAssignedAddressObject(AddressObject $assignedAddressObject): self
    {
        if (!$this->assignedAddressObjects->contains($assignedAddressObject)) {
            $this->assignedAddressObjects[] = $assignedAddressObject;
        }

        return $this;
    }

    public function removeAssignedAddressObject(AddressObject $assignedAddressObject): self
    {
        if ($this->assignedAddressObjects->contains($assignedAddressObject)) {
            $this->assignedAddressObjects->removeElement($assignedAddressObject);
        }

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }
}
