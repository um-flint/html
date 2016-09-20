<?php

namespace UMFlint\Html\Form\Input;

use UMFlint\Html\Contracts\Groupable;
use UMFlint\Html\Traits\Groupable as GroupableTrait;
use UMFlint\Html\Traits\Inlineable;

class Checkboxes extends Input implements Groupable
{
    use GroupableTrait, Inlineable {
        Inlineable::inline as traitInline;
    }

    /**
     * The default element tag.
     *
     * @var string
     */
    protected $tag = 'div';

    /**
     * @var string
     */
    protected $type = 'checkboxes';

    /**
     * Wrapper for items().
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $items
     * @return Checkboxes
     */
    public function checkboxes($items)
    {
        return $this->items($items);
    }

    /**
     * Set the items.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $items
     * @return $this
     */
    public function items($items)
    {
        foreach ($items as $key => $item) {
            $name = $this->get('name');

            if (is_array($item)) {
                if (isset($item['name'])) {
                    $name .= "[{$item['name']}]";
                    unset($item['name']);
                }
                $element = new Checkbox($name, $key);

                foreach ($item as $attribute => $value) {
                    if (method_exists($element, $attribute)) {
                        $element->$attribute($value);
                    }else {
                        $element->set($attribute, $value);
                    }
                }
            }else {
                $name .= "[]";

                $element = (new Checkbox($name, $key))
                    ->label($item);
            }

            $element->remove('id')->rules($this->rules)->showLabel(false);
            $this->appendChild($element);
        }

        $this->inlineChildren();
        $this->setChecked();

        return $this;
    }

    /**
     * Set whether inline or not.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $inline
     * @return $this
     */
    public function inline($inline = true)
    {
        $this->traitInline($inline);
        $this->inlineChildren();

        return $this;
    }

    /**
     * Render.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function __toString()
    {
        // Do some clean up before rendering.
        $this->remove('type');
        $this->remove('name');

        return parent::__toString();
    }
}