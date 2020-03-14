<?php


namespace App\Service\Products;

use App\Entity\BaseProduct;
use App\Entity\Basket;

/**
 * Class ProductsRequestData
 * @package App\Service\Products
 * Parsed and reduced request's json
 */
class ProductsRequestData
{
    /**
     * @var array
     */
    private $source;

    /**
     * @var array
     */
    public $result = [];

    /**
     * @var array
     */
    private $objects;

    private $collection = [];

    private $invalidEntities = [];

    public $basket;


    /**
     * @return array
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    private $reduced = false;


    public function __construct($source, $objects)
    {
        $this->source = $source;
        $this->objects = $objects;
        $this->basket = new Basket();
    }

    /**
     * @return array
     */
    public function getSource(): array
    {
        return $this->source;
    }

    /**
     * @param bool $reduced
     */
    public function setReduced(bool $reduced = true): void
    {
        $this->reduced = $reduced;
    }

    public function addInvalidEntity($entityId)
    {
        $this->invalidEntities[] = $entityId;
    }

    /**
     * @return array
     */
    public function getInvalidEntities(): array
    {
        return $this->invalidEntities;
    }

    public function addToCollection(BaseProduct $item)
    {
        $this->collection[$item->getId()] = $item;
    }

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }




}