<?php


namespace App\Service\Products\Reducers;

use App\Entity\BaseProduct;
use App\Entity\Discount;
use App\Service\Products\ProductsRequestData;

class PriceReducer extends ReducerAbstract
{

    private $saleApplyCats = [];

    private $saleApplyItems = [];



    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        $this->saleApplyItems = $this->basket->getItemsByProductCategoryCode($this->saleApplyCats);

        $calculatedDiscount = $this->calculateDiscount();

        $this->basket->setDiscount(new Discount($calculatedDiscount));

    }


    public function setSaleCats($catCodes)
    {
        $this->saleApplyCats = $catCodes;
    }



    private function calculateDiscount()
    {
        $count = count($this->saleApplyItems);
        $multiplier = $count > 3 ? 3 : ($count <= 1 ? 0 : $count);

        return $multiplier * 5;
    }




}