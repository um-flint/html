<?php

namespace UMFlint\Html\Traits;

use UMFlint\Html\Form\Input\Input;

trait Groupable
{
    /**
     * The current selected checkboxes.
     *
     * @var mixed
     */
    protected $value = [];

    /**
     * Hold the rules to pass on to children.
     *
     * @var string
     */
    protected $rules = '';

    /**
     * Go through and inline each child.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    public function inlineChildren()
    {
        foreach ($this->getChildren() as $child) {
            if (in_array(Inlineable::class, class_uses($child))) {
                $child->inline($this->isInline());
            }
        }
    }

    /**
     * The currently selected value.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = (array)$value;
        $this->setChecked();

        return $this;
    }

    /**
     * Check the children that we have a value for.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    protected function setChecked()
    {
        foreach ($this->getChildren() as $child) {
            if ($child instanceof Input && in_array($child->get('value'), $this->value)) {
                $child->check();
            }else {
                $child->uncheck();
            }
        }
    }

    /**
     * Set the rules for group.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = $rules;

        return $this;
    }
}