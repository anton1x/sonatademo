<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressObjectRepository")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\InternetPlan", mappedBy="assignedAddressObjects").
     * @JMS\Exclude()
     */
    private $internetPlans;

    public function __construct()
    {
        $this->internetPlans = new ArrayCollection();
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
     * @return Collection|InternetPlan[]
     */
    public function getInternetPlans(): Collection
    {
        return $this->internetPlans;
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\Groups(groups={"calculator"})
     */
    public function getInternetPlansIds()
    {
        $result = [];
        $this->getInternetPlans()->forAll(function ($key, $item) use(&$result){
            array_push($result, $item->getId());
        });

        return $result;
    }

    public function addInternetPlan(InternetPlan $internetPlan): self
    {
        if (!$this->internetPlans->contains($internetPlan)) {
            $this->internetPlans[] = $internetPlan;
            $internetPlan->addAssignedAddressObject($this);
        }

        return $this;
    }

    public function removeInternetPlan(InternetPlan $internetPlan): self
    {
        if ($this->internetPlans->contains($internetPlan)) {
            $this->internetPlans->removeElement($internetPlan);
            $internetPlan->removeAssignedAddressObject($this);
        }

        return $this;
    }
}
