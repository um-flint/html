<?php

namespace UMFlint\Html;

use Illuminate\Support\Collection;
use UMFlint\Html\Traits\HasAttributes;

class Element
{
    use HasAttributes;

    /**
     * The tag for this element..
     *
     * @var string
     */
    protected $tag;

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
     * @param $tag
     */
    public function __construct($tag)
    {
        $this->setTag($tag);

        $this->initAttributes();
        $this->initChildren();

        return $this;
    }

    /**
     * Set the type of element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $tag
     */
    protected function setTag($tag)
    {
        $this->tag = strtolower($tag);
    }

    /**
     * Get the type of element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Check if element is void.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return bool
     */
    public function isVoid()
    {
        return in_array($this->getTag(), $this->void);
    }

    /**
     * Helper to set the ID of the element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $id
     * @return $this
     */
    public function id($id)
    {
        $this->set('id', $id);

        return $this;
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
        $newClasses = (array)$class;
        $classes = explode(' ', $this->get('class'));

        foreach ($newClasses as $class) {
            if (!in_array($class, $classes)) {
                $classes[] = $class;
            }
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
        $removeClasses = (array)$class;
        $classes = explode(' ', $this->get('class'));

        foreach ($removeClasses as $class) {
            $key = array_search($class, $classes);

            if ($key !== false) {
                unset($classes[$key]);
            }
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
        $element = "<{$this->getTag()}";

        // Add each attribute to the lement.
        foreach ($this->attributes->toArray() as $attriubte => $value) {
            $element .= " {$attriubte}=\"{$value}\"";
        }

        $element .= ">";

        // Check for void element.
        if (!$this->isVoid()) {
            $element .= "{$this->renderChildren()}</{$this->getTag()}>";
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