<?php


namespace App\News;


class NewsContext
{
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getRouteCollection()
    {
        return [
            'list' => $this->getType() . '_list',
            'view' => $this->getType() . '_view',
        ];
    }

}