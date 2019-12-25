<?php


namespace App\Basket\Entity;


use App\Entity\Product;

class BasketItem
{

    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

}