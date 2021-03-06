<?php


namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Utils\TextFunctions;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class TVPlan
 * @ORM\Entity(repositoryClass="App\Repository\TVPlanRepository")
 */
class TVPlan extends BaseProduct
{
    const ROOT_CATEGORIES = ['tv'];
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", fetch="LAZY", cascade={"persist"})
     * @JMS\Groups({"calculator", "sonata_api_read"})
     * @JMS\Type("media_links_tv_plans")
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $smotreshkaId = 0;

    public function __construct()
    {
        parent::__construct();
        $this->assignedAddressObjects = new ArrayCollection();
    }

    public const type = "tv_plan";

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
     * @return int
     */
    public function getSmotreshkaId()
    {
        return $this->smotreshkaId;
    }

    /**
     * @param int $smotreshkaId
     * @return TVPlan
     */
    public function setSmotreshkaId($smotreshkaId): self
    {
        $this->smotreshkaId = $smotreshkaId;
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
            $scaleTitle .= " + кинотеатры";
        }


        return $scaleTitle;
    }


}