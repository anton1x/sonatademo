<?php


namespace App\News;


class NewsItemTypeDetector
{

    private $map = [];

    public function addType($rootCatCode, $type)
    {
        $this->map = array_merge($this->map, [$rootCatCode => $type]);
    }

    public function match($rootCatCode)
    {
        if(!isset($this->map[$rootCatCode]))
        return false;

        return $this->map[$rootCatCode];
    }

}