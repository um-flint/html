<?php

namespace UMFlint\Html\Form\Input;

use UMFlint\Html\Element;

class Select extends Input
{
    /**
     * Set the element type.
     *
     * @var string
     */
    protected $tag = 'select';

    /**
     * The current selected option.
     *
     * @var mixed
     */
    protected $value = [];

    /**
     * Select constructor.
     *
     * @param                $name
     * @param null           $value
     * @param null           $framework
     */
    public function __construct($name, $value, $framework = null)
    {
        parent::__construct($name, $value, $framework);
    }

    /**
     * Make a multiple select.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return $this
     */
    public function multiple()
    {
        $this->set('multiple', 'multiple');
        $this->name("{$this->get('name')}[]");

        return $this;
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
        $this->setSelected();

        return $this;
    }

    /**
     * Check options to value.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    protected function setSelected()
    {
        foreach ($this->getChildren() as $child) {
            // Check if this is our value.
            if ($child instanceof Element && in_array($child->get('value'), $this->value)) {
                $child->set('selected', 'selected');
            }else {
                $child->remove('selected');
            }
        }
    }

    /**
     * Set the options.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array $options
     * @return $this
     */
    public function options(array $options)
    {
        foreach ($options as $key => $option) {
            $element = (new Element('option'))
                ->set('value', $key);

            if (is_array($option)) {
                foreach ($option as $attribute => $item) {
                    if ($attribute == 'label') {
                        $element->appendChild($item);
                    }else {
                        $element->set($attribute, $item);
                    }
                }
            }else {
                $element->appendChild($option);
            }

            $this->appendChild($element);
        }

        $this->setSelected();

        return $this;
    }
}