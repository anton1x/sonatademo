<?php


namespace App\Service\Products\Reducers;

class ContactReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['contact'] ?? false) {

            $contact = $this->source['contact'];
            $this->context->setContact($contact);
        }
        else {
            $this->context->addInvalidEntity('contact');
        }

    }



}