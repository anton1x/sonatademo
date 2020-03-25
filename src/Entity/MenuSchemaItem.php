<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Tree\Traits\NestedSetEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuSchemaItemRepository")
 * @Gedmo\Tree(type="nested")
 * @JMS\ExclusionPolicy(policy="all")
 */
class MenuSchemaItem
{

    use NestedSetEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private $label;

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     */
    private $attributes = [];

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     * @JMS\SerializedName("linkAttributes")
     */
    private $linkAttributes = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Expose()
     */
    private $route;

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     */
    private $extras = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuSchemaItem", inversedBy="children", fetch="LAZY")
     * @Gedmo\TreeParent
     */
    private $parent;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuSchemaItem", mappedBy="parent", fetch="LAZY")
     * @ORM\OrderBy({"left" = "ASC"})
     * @JMS\Expose()
     */
    private $children;

    /**
     * @ORM\Column(type="array")
     * @JMS\Expose()
     */
    private $showInMenus = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private $uri = '';


    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->attributes = [
            'class' => 'menu_title',
        ];

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getLinkAttributes(): ?array
    {
        return $this->linkAttributes;
    }

    public function setLinkAttributes(array $attributes): self
    {
        $this->linkAttributes = $attributes;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getExtras(): ?array
    {
        return $this->extras;
    }

    public function setExtras(array $extras): self
    {
        $this->extras = $extras;

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

    public function __toString()
    {
        return $this->getLabel();
    }

    public function getShowInMenus(): ?array
    {
        return $this->showInMenus;
    }

    public function setShowInMenus(array $showInMenus): self
    {
        $this->showInMenus = $showInMenus;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->right;
    }





    public function getLeveledLabel()
    {
        return str_repeat('---', $this->level - 1) . ' ' . $this->getLabel();
    }


}
