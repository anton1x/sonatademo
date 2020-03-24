<?php


namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Device
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device extends BaseProduct
{

    public const type = "device";

    public const ROOT_CATEGORIES = [
        'devices_internet',
        'devices_tv',
        'devices_additional_phone',
        'devices_additional_vision'
    ];

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", fetch="EAGER", cascade={"persist"})
     * @JMS\Groups({"calculator", "sonata_api_read"})
     * @JMS\Type("media_links_devices")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ConnectionType", mappedBy="devices")
     * @JMS\Exclude()
     */
    private $connectionTypes;


    public function __construct()
    {
        parent::__construct();
        $this->image = new Media();
        $this->connectionTypes = new ArrayCollection();
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Media $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|ConnectionType[]
     */
    public function getConnectionTypes(): Collection
    {
        return $this->connectionTypes;
    }

    public function addConnectionType(ConnectionType $connectionType): self
    {
        if (!$this->connectionTypes->contains($connectionType)) {
            $this->connectionTypes[] = $connectionType;
            $connectionType->addDevice($this);
        }

        return $this;
    }

    public function removeConnectionType(ConnectionType $connectionType): self
    {
        if ($this->connectionTypes->contains($connectionType)) {
            $this->connectionTypes->removeElement($connectionType);
            $connectionType->removeDevice($this);
        }

        return $this;
    }


}