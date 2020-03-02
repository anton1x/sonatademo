<?php


namespace App\Entity;

use App\Utils\TextFunctions;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class TVPlan
 * @ORM\Entity()
 */
class TVPlan extends BaseProduct
{
    /**
     * @ORM\Column(type="integer")
     */
    private $channelCount = 0;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AddressObject", inversedBy="tvPlans")
     * @JMS\Exclude()
     */
    private $assignedAddressObjects;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @JMS\Expose()
     * @JMS\Type("boolean")
     */
    private $includeTheatres = false;

    public function __construct()
    {
        parent::__construct();
        $this->assignedAddressObjects = new ArrayCollection();
    }

    public const type = "tv_plan";

    /**
     * @return int
     */
    public function getChannelCount():int
    {
        return $this->channelCount;
    }

    /**
     * @param int $channelCount
     */
    public function setChannelCount($channelCount): void
    {
        $this->channelCount = $channelCount;
    }

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
            $assignedAddressObject->addTvPlan($this);
        }

        return $this;
    }

    public function removeAssignedAddressObject(AddressObject $assignedAddressObject): self
    {
        if ($this->assignedAddressObjects->contains($assignedAddressObject)) {
            $this->assignedAddressObjects->removeElement($assignedAddressObject);
            $assignedAddressObject->removeTvPlan($this);
        }

        return $this;
    }

    public function getIncludeTheatres(): ?bool
    {
        return $this->includeTheatres;
    }

    public function setIncludeTheatres(bool $includeTheatres): self
    {
        $this->includeTheatres = $includeTheatres;

        return $this;
    }

    /**
     * @JMS\VirtualProperty(name="scale_title")
     */
    public function getScaleTitle()
    {
        $scaleTitle =  sprintf(
            '%d %s',
            $this->getChannelCount(),
            TextFunctions::declOfNum($this->getChannelCount(), ['канал', 'канала', 'каналов'])
        );

        if ($this->getIncludeTheatres()) {
            $scaleTitle .= " + кино";
        }


        return $scaleTitle;
    }


}