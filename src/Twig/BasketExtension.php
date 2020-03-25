<?php

namespace App\Twig;

use App\Entity\Basket;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BasketExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('items_by_category', [$this, 'doItemsByCategory']),
        ];
    }


    public function doItemsByCategory(Basket $basket, $code)
    {
        return $basket->getItemsByProductCategoryCode($code);
    }
}
