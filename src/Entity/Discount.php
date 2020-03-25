<?php


namespace App\Entity;


class Discount
{

    private $percentage;


    public function __construct($percentage)
    {
        $this->percentage = $percentage;
    }

    public function getDiscountedPrice(ValueObject\Price $priceOriginal)
    {
        $newPrice = clone $priceOriginal;
        $discountedPrice =  intval(round($newPrice->getMonthlyPrice() * $this->getMultiplier()));

        $newPrice->setMonthlyPrice($discountedPrice);

        return $newPrice;
    }


    /**
     * @return mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    private function getMultiplier()
    {
        return (100 - $this->getPercentage()) / 100;
    }


}