<?php

namespace App\Entity\Sliders;

use App\Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * @ORM\Entity()
 * @Assert\Callback(callback="validate")
 */
class Slide
{

    public const SLIDE_TYPE_HTML = 1;
    public const SLIDE_TYPE_IMAGE = 2;

    public static function getAvailableTypes()
    {
        return [
            'HTML' => self::SLIDE_TYPE_HTML,
            'Картинка' => self::SLIDE_TYPE_IMAGE,
        ];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        if (!in_array($type, self::getAvailableTypes())) {
            throw new InvalidArgumentException('wrong Slide type');
        }
        $this->type = $type;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @ORM\PostPersist()
     */
    public function postPersist()
    {
        $this->setPosition($this->getId());
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Sliders\SlideGallery", inversedBy="slides")
     */
    private $slideGallery;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlideGallery(): ?SlideGallery
    {
        return $this->slideGallery;
    }

    public function setSlideGallery(?SlideGallery $slideGallery): self
    {
        $this->slideGallery = $slideGallery;

        return $this;
    }

    /**
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body='';
    /**
     * @ORM\Column(name="styles", type="text", nullable=true)
     */
    private $styles='';

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @param mixed $styles
     */
    public function setStyles($styles): void
    {
        $this->styles = $styles;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     */
    private $image;
    /**
     * @ORM\Column(name="link", type="text", nullable=true)
     */
    private $link='';

    /**
     * @return null|Media
     */
    public function getImage(): ?Media
    {
        return $this->image;
    }

    /**
     * @param Media $image
     * @return self
     */
    public function setImage(?Media $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return self
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;
        return $this;
    }

    protected function validate(ExecutionContext $context)
    {
    }


    public function __toString()
    {
        return 'SLIDE#' . $this->getId();
    }
}
