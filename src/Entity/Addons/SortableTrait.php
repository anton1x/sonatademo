<?php


namespace App\Entity\Addons;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SortableTrait
{
    /**
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition()
     */
    private $position;

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

}