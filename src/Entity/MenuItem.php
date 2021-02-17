<?php

namespace App\Entity;

use App\Entity\Traits\UpdateCreateTrait;
use App\Repository\MenuItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem as KnpMenuItem;

/**
 * @ORM\Entity(repositoryClass=MenuItemRepository::class)
 * Inspiré de: https://github.com/kevintweber/KtwDatabaseMenuBundle
 */
class MenuItem extends KnpMenuItem
{
    use UpdateCreateTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Name of this menu item (used for id by parent menu)
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $name = null;

    /**
     * Label to output, name is used by default
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $label = null;

    /**
     * Uri to use in the anchor tag
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $uri = null;

    /**
     * Array-type data
     *
     * @ORM\Column(type="array")
     */
    protected $data = array();

    /**
     * Whether the item is displayed
     *
     * @ORM\Column(type="boolean")
     */
    protected $display = true;

    /**
     * Whether the children of the item are displayed
     *
     * @ORM\Column(type="boolean")
     */
    protected $displayChildren = true;

    /**
     * Child items
     *
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent", cascade={"all"})
     */
    protected $children;

    /**
     * Parent item
     *
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent = null;

    /**
     * Constructor
     * @param string $name "The name of this menu, which is how its parent will reference it. Also used as label if label not specified"
     * @param FactoryInterface $factory
     */
    public function __construct(string $name, FactoryInterface $factory)
    {
        $this->children = new ArrayCollection();

        $this->data = [
            'attributes' => [],
            'linkAttributes' => [],
            'childrenAttributes' => [],
            'labelAttributes' => [],
            'extras' => [],
        ];

        parent::__construct($name, $factory);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAttributes(): array
    {
        return $this->data['attributes'];
    }

    public function setAttributes(array $attributes): ItemInterface
    {
        $this->data['attributes'] = $attributes;

        return $this;
    }

    public function getAttribute(string $name, $default = null)
    {
        if (isset($this->data['attributes'][$name])) {
            return $this->data['attributes'][$name];
        }

        return $default;
    }

    public function setAttribute(string $name, $value): ItemInterface
    {
        $this->data['attributes'][$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getLinkAttributes(): array
    {
        return $this->data['linkAttributes'];
    }

    /**
     * @param array $linkAttributes
     * @return ItemInterface
     */
    public function setLinkAttributes(array $linkAttributes): ItemInterface
    {
        $this->data['linkAttributes'] = $linkAttributes;

        return $this;
    }

    public function getLinkAttribute(string $name, $default = null)
    {
        if (isset($this->data['linkAttributes'][$name])) {
            return $this->data['linkAttributes'][$name];
        }

        return $default;
    }

    public function setLinkAttribute(string $name, $value): ItemInterface
    {
        $this->data['linkAttributes'][$name] = $value;

        return $this;
    }

    public function getChildrenAttributes(): array
    {
        return $this->data['childrenAttributes'];
    }

    public function setChildrenAttributes(array $childrenAttributes): ItemInterface
    {
        $this->data['childrenAttributes'] = $childrenAttributes;

        return $this;
    }

    public function getChildrenAttribute(string $name, $default = null)
    {
        if (isset($this->data['childrenAttributes'][$name])) {
            return $this->data['childrenAttributes'][$name];
        }

        return $default;
    }

    public function setChildrenAttribute(string $name, $value): ItemInterface
    {
        $this->data['childrenAttributes'][$name] = $value;

        return $this;
    }

    public function getLabelAttributes(): array
    {
        return $this->data['labelAttributes'];
    }

    public function setLabelAttributes(array $labelAttributes): ItemInterface
    {
        $this->data['labelAttributes'] = $labelAttributes;

        return $this;
    }

    public function getLabelAttribute(string $name, $default = null)
    {
        if (isset($this->data['labelAttributes'][$name])) {
            return $this->data['labelAttributes'][$name];
        }

        return $default;
    }

    public function setLabelAttribute(string $name, $value): ItemInterface
    {
        $this->data['labelAttributes'][$name] = $value;

        return $this;
    }

    public function getExtras(): array
    {
        return $this->data['extras'];
    }

    public function setExtras(array $extras): ItemInterface
    {
        $this->data['extras'] = $extras;

        return $this;
    }

    public function getExtra(string $name, $default = null)
    {
        if (isset($this->data['extras'][$name])) {
            return $this->data['extras'][$name];
        }

        return $default;
    }

    public function setExtra(string $name, $value): ItemInterface
    {
        $this->data['extras'][$name] = $value;

        return $this;
    }

    public function toArray($depth = null): array
    {
        $array = array(
            'name' => $this->name,
            'label' => $this->label,
            'uri' => $this->uri,
            'attributes' => $this->getAttributes(),
            'labelAttributes' => $this->getLabelAttributes(),
            'linkAttributes' => $this->getLinkAttributes(),
            'childrenAttributes' => $this->getChildrenAttributes(),
            'extras' => $this->getExtras(),
            'display' => $this->display,
            'displayChildren' => $this->displayChildren,
        );

        // export the children as well, unless explicitly disabled
        if (0 !== $depth) {
            $childDepth = (null === $depth) ? null : $depth - 1;
            $array['children'] = array();
            foreach ($this->children as $key => $child) {
                $array['children'][$key] = $child->toArray($childDepth);
            }
        }

        return $array;
    }

    public function addChild($child, array $options = []): ItemInterface
    {
        if (!($child instanceof ItemInterface)) {
            $child = $this->factory->createItem($child, $options);
        } elseif (null !== $child->getParent()) {
            throw new InvalidArgumentException('Cannot add menu item as child, it already belongs to another menu (e.g. has a parent).');
        }

        $child->setParent($this);

        $this->children->set($child->getName(), $child);

        return $child;
    }

    public function getChild(string $name): ?ItemInterface
    {
        return $this->children->get($name);
    }

    public function getChildren(): array
    {
        return $this->children->toArray();
    }

    public function setChildren(array $children): ItemInterface
    {
        $this->children = new ArrayCollection($children);

        return $this;
    }

    public function removeChild($name): ItemInterface
    {
        $name = $name instanceof ItemInterface ? $name->getName() : $name;

        $child = $this->getChild($name);
        if ($child !== null) {
            $child->setParent(null);
            $this->children->remove($name);
        }

        return $this;
    }

    public function getFirstChild(): ItemInterface
    {
        return $this->children->first();
    }

    public function getLastChild(): ItemInterface
    {
        return $this->children->last();
    }

    /**
     * Synonymous with 'isDisplayed'.
     *
     * @return boolean
     */
    public function getDisplay(): bool
    {
        return $this->display;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
