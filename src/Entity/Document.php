<?php

namespace App\Entity;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Addons\SortableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Document
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="children")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    private $position=0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentGroup", inversedBy="documents")
     */
    private $documentGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     */
    private $file;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getDocumentGroup(): ?DocumentGroup
    {
        return $this->documentGroup;
    }

    public function setDocumentGroup(?DocumentGroup $documentGroup): self
    {
        $this->documentGroup = $documentGroup;

        $children = $this->getChildren();
        if (null !== $children) {
            foreach ($children as $child) {
                $child->setDocumentGroup($documentGroup);
            }
        }

        $parent = $this->getParent();

        if (null !== $parent && $documentGroup !== $parent->getDocumentGroup()) {
           $this->setDocumentGroup($parent->getDocumentGroup());
        }

        return $this;
    }

    public function getFile(): ?Media
    {
        return $this->file;
    }

    public function setFile(?Media $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getFileExtension()
    {
        $filename = $this->getFile()->getMetadataValue('filename');
        $filename = explode('.', $filename);

        if (!isset($filename[1]))
            return;

        return $filename[1];
    }

    /**
     * @ORM\PostPersist
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->setPosition($this->id * 10);

        $args->getObjectManager()->flush($this);
    }

    public function __toString()
    {
        return $this->getName();
    }

}
