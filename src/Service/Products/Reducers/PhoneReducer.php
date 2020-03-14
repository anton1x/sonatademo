<?php


namespace App\Service\Products\Reducers;


use App\Service\Products\ProductsRequestData;

class PhoneReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['phone'] ?? false) {

            $planId = $this->source['phone']['phoneTariff'] ?? false;
            if (!$planId)
                return;

            $phone = $this->parseSingle($planId, 'phonePlan', 'plan', 'additional_phone', false);

            if (!$phone)
                return;

            $dectList = $this->source['phone']['dectDevices'] ?? false;
            if ($dectList)
                $this->parseMultiple($dectList, 'dectDevice', 'devices', 'devices_additional_phone_dect', true);

            $tableList = $this->source['phone']['tableDevices'] ?? false;
            if ($tableList) {
                $this->parseMultiple($tableList, 'tableDevice', 'devices', 'devices_additional_phone_table', true);
            }


        }

    }


    public function getResultRoot()
    {
        return 'phone';
    }


}