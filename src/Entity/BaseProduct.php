<?php


namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\ValueObject\Price;
use App\Entity\ValueObject\SeoInfo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Product
 * @ORM\Entity()
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     InternetPlan::type = "InternetPlan",
 *     TVPlan::type = "TVPlan",
 *     AdditionalServicePlan::type = "AdditionalServicePlan"
 *     }
 *    )
 */
abstract class BaseProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Groups(groups={"calculator"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"calculator"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=1023, nullable=true)
     * @JMS\Groups({"calculator"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", fetch="LAZY", cascade={"persist"})
     * @JMS\Groups({"calculator", "sonata_api_read"})
     * @JMS\Type("media_links")
     */
    private $image;



    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\ClassificationBundle\Entity\Category", fetch="LAZY")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     * @JMS\Groups({"calculator"})
     * @JMS\Type("category_code")
     */
    private $category;


    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Price")
     * @JMS\Groups(groups={"calculator"})
     */
    private $price;


    public function __construct()
    {
        $this->image = new Media();
        $this->seoInfo = new SeoInfo();
        $this->price = new Price();
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
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price
     */
    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }




    public function __toString():string
    {
        return $this->title;
    }


}