<?php


namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 */
class Product implements SeoPoweredInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=1023)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", fetch="LAZY", cascade={"persist"})
     */
    private $image;



    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\ClassificationBundle\Entity\Category", fetch="LAZY")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SeoInfo", cascade={"persist"}, orphanRemoval=true)
     */
    private $seoInfo;

    public function __construct()
    {
        $this->image = new Media();
        $this->seoInfo = new SeoInfo();
    }

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
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

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Media $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return SeoInfo
     */
    public function getSeoInfo(): SeoInfo
    {
        return $this->seoInfo ? $this->seoInfo : new SeoInfo();
    }

    /**
     * @param SeoInfo $seoInfo
     */
    public function setSeoInfo(SeoInfo $seoInfo): void
    {
        $this->seoInfo = $seoInfo;
    }



    public function __toString():string
    {
        return $this->title;
    }

    public function getRawContent()
    {
        return '';
    }

    public function getContent()
    {
        return '';
    }

    public function setContent($content)
    {
        
    }

    public function getFormat()
    {
        return '';
    }

    public function getDescriptionFormatter()
    {
        return '';
    }

    public function getRawDescription()
    {
        return '';
    }


}