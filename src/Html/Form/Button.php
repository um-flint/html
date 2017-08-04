<?php

namespace UMFlint\Html\Form;

use UMFlint\Html\Element;

class Button extends Element
{
    public function __construct($text, $type = 'submit')
    {
        parent::__construct('button');
        $this->text($text);
        $this->type($type);

        return $this;
    }

    /**
     * Set the text for button.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $text
     * @return $this
     */
    public function text($text)
    {
        $this->emptyChildren();
        $this->appendChild($text);

        return $this;
    }

    /**
     * Set the type of button.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->set('type', $type);

        return $this;
    }
}