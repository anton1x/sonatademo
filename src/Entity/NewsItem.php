<?php

namespace App\Entity;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\ValueObject\SeoInfo;
use App\Utils\TextFunctions;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsItemRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class NewsItem implements SeoPoweredInterface, ActivatedInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @JMS\Expose()
     * @JMS\SerializedName("name")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", fetch="LAZY", cascade={"persist"}, fetch="EAGER")
     * @JMS\Expose()
     * @JMS\Type("media_link_news")
     */
    private $image;

    /**
     * @JMS\Expose()
     * @JMS\SerializedName("url")
     */
    private $systemURL;

    /**
     * @param mixed $systemURL
     */
    public function setSystemURL($systemURL): void
    {
        $this->systemURL = $systemURL;
    }

    /**
     * @return mixed
     */
    public function getImage(): ?Media
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage(?Media $image): void
    {
        $this->image = $image;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     * @JMS\Expose()
     * @JMS\SerializedName("desc")
     */
    private $preview;

    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\SeoInfo")
     */
    private $seoInfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var Category $category
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\ClassificationBundle\Entity\Category")
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     */
    private $itemType;


    public function __construct()
    {
        $this->seoInfo = new SeoInfo();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->setPublishedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }



    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setUpdatedAt(new \DateTime());
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


    public function getPublishedAt():?\DateTimeInterface
    {
        return $this->publishedAt;
    }


    public function setPublishedAt($publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemType()
    {
        return $this->itemType;
    }



    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function assignItemType()
    {
        $this->itemType = $this->getCategory()->getContext()->getId();
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("date")
     * @JMS\Expose()
     */
    public function getRusFormattedDate()
    {
        return TextFunctions::rusDate($this->getPublishedAt());
    }

}
