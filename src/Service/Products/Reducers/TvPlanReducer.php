<?php


namespace App\Service\Products\Reducers;


use App\Service\Products\ProductsRequestData;

class TvPlanReducer extends ReducerAbstract
{

    public function reduce()
    {
        if (!$this->isInitialized)
            throw new \Exception('You should init reducer with init() before use reduce()');

        if ($this->source['tv'] ?? false) {

//            $planId = $this->source['internet']['internetPlan'];
//            $this->parseSingle($planId, 'internetPlan', 'plan', 'internet_basic', false);
//
            $planId = $this->source['tv']['tvPlan'] ?? false;
            if(!$planId)
                return;

            $this->parseSingle($planId, 'tvPlan', 'plan', 'tv_basic', false);

            $tvBox = $this->source['tv']['tvBox'] ?? false;
            if ($tvBox)
                $this->parseSingle($tvBox, 'tvBox', 'devices', 'devices_tv_box', true);

            $addons = $this->source['tv']['addons'] ?? false;
            if ($addons) {
                $this->parseMultiple($addons, 'tvAddons', 'additional', 'tv_addons');
            }

            $theatres = $this->source['tv']['theathers'] ?? false;
            if ($theatres) {
                $this->parseMultiple($theatres, 'tvTheatres', 'additional', 'tv_theatres');
            }

        }

    }



    public function getResultRoot()
    {
        return 'tv';
    }


}