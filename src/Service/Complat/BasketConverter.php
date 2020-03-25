<?php
/**
 * Converts Basket to the array for complat request
 */

namespace App\Service\Complat;


use App\Entity\Basket;
use App\Entity\BasketItem;

class BasketConverter
{
    public static function convert(Basket $basket)
    {
        $result = [];

        foreach ($basket->getItems() as $item) {
            /**
             * @var BasketItem $item
             */
            $converted = [];
            $converted['id'] = $item->getProduct()->getId();
            $converted['price'] = $item->getDiscountedPrice()->getSummarizedValue();
            $converted['count'] = $item->getCount();
            $converted['type'] = $item->getProduct()->getCategory()->getCode();

            $result[] = $converted;
        }

        return $result;
    }
}