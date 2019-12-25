<?php


namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeoInfo
 * @package App\Entity
 * @ORM\Entity()
 */
class SeoInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $meta;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $description;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }



}