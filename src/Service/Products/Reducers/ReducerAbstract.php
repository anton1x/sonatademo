<?php


namespace App\Service\Products\Reducers;


use App\Entity\BasketItem;
use App\Service\Products\ProductsRequestData;

abstract class ReducerAbstract implements ReducerInterface
{

    protected $isInitialized = false;

    protected $addresses;
    protected $products;
    /**
     * @var array
     */
    protected $source;
    /**
     * @var array
     */
    protected $result;

    protected $products_grouped;
    /**
     * @var ProductsRequestData
     */
    protected $context;
    /**
     * @var \App\Entity\Basket
     */
    protected $basket;

    abstract public function reduce();


    protected function parseSingle($sourceItem, $type, $resultKey, $group = null, $mergeResultAsArray = true, $countable = false)
    {
        if ($countable) {
            $count = $sourceItem['count'];
            $sourceItem = $sourceItem['id'];
        }

        $groupIsCorrect = $this->isGroupCorrect($sourceItem, $group);

        if ($sourceItem && $groupIsCorrect) {
            $basketItem = new BasketItem($this->products[$sourceItem], $count ?? 1);
            //$value = $countable ? $basketItem : $this->products[$sourceItem];
            //$valueToPut = $mergeResultAsArray ? [$resultKey => [$value]] : [$resultKey => $value];

            $this->putToBasket($basketItem);
            //$this->putToResult($valueToPut);
            //$this->context->addToCollection($this->products[$sourceItem]);
            return true;
        } else {
            $this->context->addInvalidEntity($type);
            return false;
        }
    }

    protected function parseMultiple($sourceItems, $type, $resultKey, $group = null, $countable = false)
    {
        foreach ($sourceItems as $sourceItem) {
            $this->parseSingle($sourceItem, $type, $resultKey, $group, true, $countable);
        }

    }


    protected function parseBoolean($sourceItem, $type, $resultKey, $group, $mergeResultAsArray = true)
    {
        if ($sourceItem) {
            $product = array_values($this->products_grouped[$group])[0] ?? false;

            if (!$product) {
                $this->context->addInvalidEntity($type);
                return false;
            }

//            $this->putToResult([
//                $resultKey => [
//                     $product,
//                ]
//            ]);

            $this->putToBasket(new BasketItem($product));

//            $this->context->addToCollection($product);

            return true;
        } else {

            return false;

        }

    }

    public function init(ProductsRequestData $context)
    {
        $this->source = $context->getSource();
        $this->products = $context->getObjects()['products_all'];
        $this->products_grouped = $context->getObjects()['products'];
        $this->addresses = $context->getObjects()['addresses'];

        $this->context = $context;

        $this->result = &$context->result;

        $this->isInitialized = true;

        $this->basket = $context->basket;
    }


    protected function putToBasket(BasketItem $item) {
        $this->basket->addItem($item);
    }


    /**
     * @param $sourceItem
     * @param $group
     * @return bool
     */
    protected function isGroupCorrect($sourceItem, $group): bool
    {
        $groupIsCorrect = $group ? isset($this->products_grouped[$group][$sourceItem]) : true;
        return $groupIsCorrect;
    }


}