<?php

namespace Prodigious\Sonata\MenuBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MenuItem
 */
#[ORM\MappedSuperclass]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\Table(name: 'sonata_menu_item')]
abstract class MenuItem implements MenuItemInterface
{
    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    protected string $name = '';

    /**
     * @var ?string
     */
    #[ORM\Column(name: 'url', type: 'string', length: 255, nullable: true)]
    protected ?string $url = null;

    /**
     * @var ?string
     */
    #[ORM\Column(name: 'class_attribute', type: 'string', length: 255, nullable: true)]
    protected ?string $classAttribute = null;

    /**
     * @var ?int
     */
    #[ORM\Column(name: 'position', type: 'smallint', options: ['unsigned' => true], nullable: true)]
    protected ?int $position = null;

    /**
     * @var ?bool
     */
    #[ORM\Column(name: 'target', type: 'boolean', nullable: true, options: ['default' => false])]
    protected ?bool $target = false;

    /**
     * @var ?bool
     */
    #[ORM\Column(name: 'enabled', type: 'boolean', nullable: true, options: ['default' => true])]
    protected ?bool $enabled = true;

    /**
     * @var \stdClass
     *
     */
    protected $page;

    /**
     * @var ?MenuItemInterface
     */
    #[ORM\ManyToOne(targetEntity: MenuItemInterface::class, inversedBy: 'children', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'parent', referencedColumnName: 'id', onDelete: 'SET NULL', nullable: true)]
    protected ?MenuItemInterface $parent = null;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: MenuItemInterface::class, mappedBy: 'parent', cascade: ['all'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    protected $children;

    /**
     * @var ?MenuInterface
     */
    #[ORM\ManyToOne(targetEntity: MenuInterface::class, inversedBy: 'menuItems')]
    #[ORM\JoinColumn(name: 'menu', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    protected ?MenuInterface $menu = null;

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->position = 999;
        $this->enabled = true;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MenuItem
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return MenuItem
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Set classAttribute
     *
     * @param ?string $classAttribute
     * @return MenuItem
     */
    public function setClassAttribute(?string $classAttribute): static
    {
        $this->classAttribute = $classAttribute;

        return $this;
    }

    /**
     * Get classAttribute
     *
     * @return ?string
     */
    public function getClassAttribute(): ?string
    {
        return $this->classAttribute;
    }

    /**
     * Set position
     *
     * @param ?int $position
     * @return MenuItem
     */
    public function setPosition(?int $position)
    {
        $this->position = (int)$position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int 
     */
    public function getPosition(): int
    {
        return (int)$this->position;
    }

    /**
     * Set target
     *
     * @param bool $target
     */
    public function setTarget(bool $target): static
    {
        $this->target = (bool)$target;

        return $this;
    }

    /**
     * Get target
     *
     * @return bool
     */
    public function getTarget(): bool
    {
        return (bool)$this->target;
    }

    /**
     * Set enabled
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        if(!$enabled && $this->hasChild()) {
            foreach ($this->children as $child) {
                if($child->enabled) {
                    $child->setEnabled(false);
                    $child->setParent(null);
                }
            }
            $this->children = new ArrayCollection();
        }

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled(): bool
    {
        return (bool)$this->enabled;
    }

    /**
     * Get page
     *
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     *
     * @param $page
     *
     * @return MenuItem
     */
    public function setPage($page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get parent
     *
     * @return ?MenuItemInterface
     */
    public function getParent(): ?MenuItemInterface
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param ?MenuItemInterface $parent
     *
     * @return MenuItem
     */
    public function setParent(?MenuItemInterface $parent): static
    {
        $this->parent = $parent;
        
        if(!is_null($parent)) {
            $parent->addChild($this);
        }

        return $this;
    }

    /**
     * Add child
     *
     * @param MenuItemInterface $child
     *
     * @return $this
     */
    public function addChild(MenuItemInterface $child): static
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param MenuItemInterface $child
     */
    public function removeChild(MenuItemInterface $child): void
    {
        $this->children->removeElement($child);
    }

    /**
     * Set children
     *
     * @param Collection $children
     *
     * @return MenuItem
     */
    public function setChildren(Collection $children): static
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * Set menu
     *
     * @param MenuInterface $menu
     *
     * @return MenuItem
     */
    public function setMenu(MenuInterface $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return MenuInterface
     */
    public function getMenu(): MenuInterface
    {
        return $this->menu;
    }

    /**
     * Has child
     */
    public function hasChild(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * Has parent
     */
    public function hasParent(): bool
    {
        return !is_null($this->parent);
    }

    /**
     * @return MenuItemInterface[]
     */
    public function getActiveChildren(): array
    {
        $children = [];

        foreach ($this->children as $child) {
            if($child->enabled) {
                $children[] = $child;
            }
        }

        return $children;
    }

    public function __toString(): string
    {
        return $this->name ?? "";
    }
}
