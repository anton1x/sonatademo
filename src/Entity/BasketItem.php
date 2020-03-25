<?php


namespace App\Entity;


use App\Entity\Basket;
use App\Entity\ValueObject\Price;

class BasketItem
{
    /**
     * @var BaseProduct
     */
    private $product;
    /**
     * @var int
     */
    private $count;

    private $discount = null;

    public function __construct(BaseProduct $product, $count = 1)
    {
        $this->product = $product;
        $this->count = $count;
    }

    /**
     * @return BaseProduct
     */
    public function getProduct(): BaseProduct
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    public function getPrice()
    {
        $price =  clone $this->getProduct()->getPrice();
        $monthlyPrice = $this->count * $this->getProduct()->getPrice()->getMonthlyPrice();
        $price->setMonthlyPrice($monthlyPrice);

        $connectionPrice = $this->count * $this->getProduct()->getPrice()->getConnectionPrice();
        $price->setConnectionPrice($connectionPrice);

        return $price;
    }

    public function getDiscountedPrice(): Price
    {
        $priceOriginal =  $this->getPrice();

        if (null == $this->discount) {
            return $priceOriginal;
        }

        return $this->discount->getDiscountedPrice($priceOriginal);

    }

    /**
     * @param null|Discount $discount
     */
    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }


}