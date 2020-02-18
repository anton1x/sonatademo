<?php


namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Price
 * @package App\Entity\ValueObject
 * @ORM\Embeddable()
 */
class Price
{
    /**
     * @ORM\Column(type="integer")
     * @JMS\Groups({"calculator"})
     */
    private $connectionPrice = 0;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Groups({"calculator"})
     */
    private $monthlyPrice = 0;

    public const currency = '₽';

    /**
     * @return mixed
     */
    public function getConnectionPrice()
    {
        return $this->connectionPrice;
    }

    /**
     * @param mixed $connectionPrice
     */
    public function setConnectionPrice($connectionPrice): void
    {
        $this->connectionPrice = $connectionPrice;
    }

    /**
     * @return mixed
     */
    public function getMonthlyPrice()
    {
        return $this->monthlyPrice;
    }

    /**
     * @param mixed $monthlyPrice
     */
    public function setMonthlyPrice($monthlyPrice): void
    {
        $this->monthlyPrice = $monthlyPrice;
    }

    public function getFormattedMonthlyPrice()
    {
        return sprintf('%d %s', $this->getMonthlyPrice(), self::currency);
    }


}