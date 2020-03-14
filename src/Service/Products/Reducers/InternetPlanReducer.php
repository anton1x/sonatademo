<?php


namespace App\Service\Products\Reducers;


use App\Service\Products\ProductsRequestData;

class InternetPlanReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['internet'] ?? false) {

            $planId = $this->source['internet']['internetPlan'] ?? false;

            if (!$planId)
                return;

            $isPlanCorrect = $this->parseSingle($planId, 'internetPlan', 'plan', 'internet_basic', false);

            if (!$isPlanCorrect)
                return;

            $staticIp = $this->source['internet']['staticIp'] ?? false;

            $this->parseBoolean($staticIp, 'staticIp', 'additional', 'additional_internet');


            $optDevice = $this->source['internet']['optDevice'] ?? false;

            if ($optDevice)
                $this->parseSingle($optDevice, 'optDevice', 'devices', 'devices_internet_ont', true);


            $wifiDevice = $this->source['internet']['wifiDevice'] ?? false;

            if ($wifiDevice)
                $this->parseSingle($wifiDevice, 'wifiDevice', 'devices', 'devices_internet_wifi', true);

        }

    }


    public function getResultRoot()
    {
        return 'internet';
    }


}