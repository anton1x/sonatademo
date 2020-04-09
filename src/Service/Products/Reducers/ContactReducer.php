<?php


namespace App\Service\Products\Reducers;

class ContactReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['contact'] ?? false) {

            if (
                $this->source['contact']['input_building'] ?? false &&
                $this->source['contact']['input_apartment'] ?? false
            ) {
                $this->source['contact']['input_building'] = (int) $this->source['contact']['input_building'];
                $this->source['contact']['input_apartment'] = (int) $this->source['contact']['input_apartment'];
            }

            $contact = $this->source['contact'];
            $this->context->setContact($contact);


        }
        else {
            $this->context->addInvalidEntity('contact');
        }

    }



}