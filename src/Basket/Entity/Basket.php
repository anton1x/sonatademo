<?php


namespace App\Basket\Entity;


use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;

class Basket
{
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addItem(BasketItem $item)
    {
        if(!$this->hasProduct($item->getProduct()))
        {
            $this->items->add($item);
        }
    }

    public function hasProduct(Product $product)
    {
        foreach ($this->items as $item)
        {
            if($item->getProduct()->getId() == $product->getId())
                return true;
        }

        return false;
    }

    public function removeItem(BasketItem $item)
    {
        $this->items->removeElement($item);
    }

    public function getTotalPrice()
    {
        $price = 0;

        foreach ($this->items as $item)
        {
            $price += $item->getProduct()->getPrice();
        }
    }

}