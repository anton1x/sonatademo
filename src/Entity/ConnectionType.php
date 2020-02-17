<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConnectionTypeRepository")
 */
class ConnectionType
{

    public const DEVICES_INTERNET_CATEGORY = 'devices_internet';

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Device", inversedBy="connectionTypes")
     * @JMS\Type("object_ids")
     */
    private $devices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AddressObject", mappedBy="connectionType")
     * @JMS\Exclude()
     */
    private $addresses;


    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->addresses = new ArrayCollection();
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
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->contains($device)) {
            $this->devices->removeElement($device);
        }

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
            $address->setConnectionType($this);
        }

        return $this;
    }

    public function removeAddress(AddressObject $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getConnectionType() === $this) {
                $address->setConnectionType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
