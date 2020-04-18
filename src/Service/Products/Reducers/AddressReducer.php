<?php


namespace App\Service\Products\Reducers;

use App\Entity\AddressObject;

class AddressReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['addressId'] ?? false) {

            $id = $this->source['addressId'] ?? false;
            if (!$id)
                return;

            $filtered = array_filter($this->addresses, function ($addr) use ($id) {

                /** @var AddressObject $addr */
                return $addr->getId() == $id;
            });

            if (!count($filtered)) {
                $this->context->addInvalidEntity('address');
                return;
            }

            $address = array_shift($filtered);

            $this->context->setAddress($address);


        }

    }



}