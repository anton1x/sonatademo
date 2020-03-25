<?php

namespace App\Entity;

use App\Entity\ValueObject\SeoInfo;

interface SeoPoweredInterface
{
    public function getSeoInfo();

    public function setSeoInfo(SeoInfo $seoInfo);
}