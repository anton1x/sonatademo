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
     * @ORM\Column(type="integer")
     */
    private $speed = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PricingType", inversedBy="internetPlans")
     * @ORM\JoinColumn(nullable=true)
     * @JMS\Exclude()
     */
    private $pricingType;

    public function __construct()
    {
        parent::__construct();
    }

    public const type = 'internet_plan';


    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * @JMS\VirtualProperty(name="scale_title")
     */
    public function getScaleTitle()
    {
        return sprintf('%d мбит/c', $this->getSpeed());
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
