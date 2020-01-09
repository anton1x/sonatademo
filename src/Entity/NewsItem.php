<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsItemRepository")
 */
class NewsItem implements SeoPoweredInterface, ActivatedInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

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
     * @Assert\NotNull()
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $preview;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SeoInfo", cascade={"persist"}, orphanRemoval=true)
     */
    private $seoInfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;


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


}
