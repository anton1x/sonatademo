<?php


namespace App\Service\Products;

use App\Entity\AddressObject;
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

    private $address;

    private $collection = [];

    private $invalidEntities = [];

    private $complatLogin;

    public $basket;

    private $contact;


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


    /**
     * @return mixed
     */
    public function getComplatLogin()
    {
        return $this->complatLogin;
    }

    /**
     * @param mixed $complatLogin
     */
    public function setComplatLogin($complatLogin): void
    {
        $this->complatLogin = $complatLogin;
    }


    public function toResponse()
    {
        if ($this->getInvalidEntities()) {
            return ProductsResponseData::createErrorResponse();
        }

        return ProductsResponseData::create($this);
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress(AddressObject $address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    public function getContactParam($param)
    {
        return $this->contact[$param] ?? '';
    }

    public function getConnectionDate()
    {
        if (!isset($this->contact['connect_time'])) {
            return null;
        }
        $time = $this->contact['connect_time'];

        return sprintf('%d.%d.%d %d:%s', $time['day'], $time['month'], $time['year'], $time['hour_start'], '00');
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact): void
    {
        $this->contact = $contact;
    }

    public function needLoginCreate() {
        $contactType =  $this->getContact()['type'] ?? false;

        //$contactAlreadyClient = $this->getContact()['checkbox_already_client'] ?? false;

        return $contactType == "self";
    }

}