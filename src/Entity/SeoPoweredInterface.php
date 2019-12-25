<?php

namespace App\Entity;

interface SeoPoweredInterface
{
    public function getSeoInfo();

    public function setSeoInfo(SeoInfo $seoInfo);
}