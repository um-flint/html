<?php

namespace UMFlint\Html;

use Illuminate\Support\Collection;
use UMFlint\Html\Traits\HasAttributes;

class Element
{
    use HasAttributes;

    /**
     * The type of element.
     *
     * @var string
     */
    protected $type;

    /**
     * The children for this element.
     *
     * @var Collection
     */
    protected $children;

    /**
     * Self closing HTML5 elemnts.
     *
     * @see https://www.w3.org/TR/html5/syntax.html#void-elements
     * @var array
     */
    protected $void = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    /**
     * Element constructor.
     *
     * @param $type
     */
    public function __construct($type)
    {
        $this->setType($type);

        $this->initAttributes();
        $this->initChildren();

        return $this;
    }

    /**
     * Set the type of element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $type
     */
    protected function setType($type)
    {
        $this->type = strtolower($type);
    }

    /**
     * Get the type of element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Check if element is void.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return bool
     */
    public function isVoid()
    {
        return in_array($this->getType(), $this->void);
    }

    /**
     * Adds the passed class to the class attribute if it does not already exist.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $class
     * @return $this
     */
    public function addClass($class)
    {
        $classes = explode(' ', $this->get('class'));

        if (!in_array($class, $classes)) {
            $classes[] = $class;
        }

        $this->set('class', trim(implode(' ', $classes), ' '));

        return $this;
    }

    /**
     * Searches class attribute and removes the passed class.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $class
     * @return $this
     */
    public function removeClass($class)
    {
        $classes = explode(' ', $this->get('class'));

        if ($key = array_search($class, $classes) !== false) {
            unset($classes[$key]);
        }

        $this->set('class', trim(implode(' ', $classes), ' '));

        return $this;
    }

    /**
     * Create new collection for children.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    protected function initChildren()
    {
        $this->children = new Collection();
    }

    /**
     * Append new child.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array|string $element
     * @return $this
     * @throws \Exception
     */
    public function appendChild($element)
    {
        if ($this->isVoid()) {
            throw new \Exception('Cannot append a child on a void element.');
        }

        if (is_array($element)) {
            foreach ($element as $child) {
                $this->children->push($child);
            }
        }else {
            $this->children->push($element);
        }

        return $this;
    }

    /**
     * Prepend new child
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array|string $element
     * @return $this
     * @throws \Exception
     */
    public function prependChild($element)
    {
        if ($this->isVoid()) {
            throw new \Exception('Cannot prepend a child on a void element.');
        }

        if (is_array($element)) {
            foreach ($element as $child) {
                $this->children->prepend($child);
            }
        }else {
            $this->children->prepend($element);
        }

        return $this;
    }

    /**
     * Empty out children collection.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return $this
     */
    public function emptyChildren()
    {
        $this->initChildren();

        return $this;
    }

    /**
     * Get the elements children.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Render the children.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    protected function renderChildren()
    {
        $return = '';

        foreach ($this->getChildren()->toArray() as $child) {
            $return .= $child;
        }

        return $return;
    }

    /**
     * Render the element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function render()
    {
        $element = "<{$this->getType()}";

        // Add each attribute to the lement.
        foreach ($this->attributes->toArray() as $attriubte => $value) {
            $element .= " {$attriubte}=\"{$value}\"";
        }

        $element .= ">";

        // Check for void element.
        if (!$this->isVoid()) {
            $element .= "{$this->renderChildren()}</{$this->getType()}>";
        }

        return $element;
    }

    /**
     * Render element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}