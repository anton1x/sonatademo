<?php


namespace App\Entity;


interface ActivatedInterface
{

    public function getIsActive():?bool;

    public function setIsActive(bool $isActive);

}