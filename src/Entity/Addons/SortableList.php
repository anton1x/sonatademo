<?php


namespace App\Entity\Addons;


interface SortableList
{
    public function getPosition(): ?int;

    public function setPosition(int $position);
}