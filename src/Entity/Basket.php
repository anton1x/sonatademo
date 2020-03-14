<?php


namespace App\Entity;


use App\Entity\ValueObject\Price;

class Basket
{
    /**
     * @var BasketItem[] $items
     */
    private $items = [];
    /**
     * @var Price
     */
    private $price;

    /**
     * @var null|Discount
     */
    private $discount = null;


    public function __construct()
    {
        $this->price = new Price();
    }

    public function addItem(BasketItem $item)
    {
        $this->items[$item->getProduct()->getId()] = $item;

        $this->price->plus($item->getPrice());

        $item->setDiscount($this->discount);

    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getDiscountedPrice()
    {
        return $this->discount->getDiscountedPrice($this->getPrice());
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount(Discount $discount): void
    {
        $this->discount = $discount;

        foreach ($this->items as $item) {
            if ($item->getProduct()->canBeDiscounted()) {
                $item->setDiscount($discount);
            }

        }
    }

    public function getItemsByProductCategoryCode($codes)
    {
        if (!is_array($codes))
            $codes = [$codes];
        
        $result = array_filter($this->getItems(), function (BasketItem $item) use ($codes) {
            return in_array($item->getProduct()->getCategory()->getCode(), $codes);
        });

        return $result;
    }

    /**
     * @return Discount|null
     */
    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function getFirstPaymentValue()
    {
        return $this->getDiscountedPrice()->getSummarizedValue();
    }


}