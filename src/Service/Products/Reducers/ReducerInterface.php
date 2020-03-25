<?php


namespace App\Service\Products\Reducers;

use App\Service\Products\ProductsRequestData;

interface ReducerInterface
{
    public function reduce();

    public function init(ProductsRequestData $context);
}