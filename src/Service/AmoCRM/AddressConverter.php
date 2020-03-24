<?php


namespace App\Service\AmoCRM;


use App\Entity\AddressObject;
use App\Service\Products\ProductsRequestData;

class AddressConverter
{
    /**
     * @var ProductsRequestData
     */
    private $requestData;

    public function __construct(ProductsRequestData $requestData)
    {

        $this->requestData = $requestData;
    }

    private function formatAddress(AddressObject $addressObject)
    {
        return sprintf('%s (%s)', $addressObject->getTitle(), $addressObject->getAddress());
    }

    public function getAddress()
    {
        return $this->formatAddress($this->requestData->getAddress());
    }
}