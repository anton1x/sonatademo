<?php


namespace App\Service\Products\Reducers;


use App\Service\Products\ProductsRequestData;

class VisionParkingReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['vision'] ?? false) {

            $planId = $this->source['vision']['visionParkingTariff'] ?? false;
            if (!$planId)
                return;

            $poePort = $this->source['vision']['poePort'] ?? false;

            $this->parseBoolean($poePort, 'poePort', 'additional', 'additional_vision_poe');

            $vision = $this->parseSingle($planId, 'visionParking', 'plan', 'additional_vision_parking', false);

            if (!$vision)
                return;

            $devices = $this->source['vision']['devicesParking'] ?? false;
            if ($devices)
                $this->parseMultiple($devices, 'deviceParking', 'devices', 'devices_additional_vision_parking', true);


        }

    }


}