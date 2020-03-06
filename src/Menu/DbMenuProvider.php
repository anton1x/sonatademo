<?php


namespace App\Menu;


use Doctrine\Common\Collections\ArrayCollection;

class DbMenuProvider
{

    private $menuCollection;

    public function __construct()
    {
        $this->menuCollection = new \SplObjectStorage();
    }

    public function addMenuItem(iterable $items)
    {
    }

    public function getMenuCollection()
    {
        return $this->menuCollection;
    }

}