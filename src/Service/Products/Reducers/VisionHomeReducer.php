<?php


namespace App\Service\Products\Reducers;


use App\Service\Products\ProductsRequestData;

class VisionHomeReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['vision'] ?? false) {

            $planId = $this->source['vision']['visionHomeTariff'] ?? false;
            if (!$planId)
                return;

            $vision = $this->parseSingle($planId, 'visionHome', 'plan', 'additional_vision_home', false);

            if (!$vision)
                return;

            $devices = $this->source['vision']['devicesHome'] ?? false;
            if ($devices)
                $this->parseMultiple($devices, 'deviceHome', 'devices', 'devices_additional_vision_home', true);


        }

    }


}