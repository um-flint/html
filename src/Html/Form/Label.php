<?php

namespace UMFlint\Html\Form;

use UMFlint\Html\Element;

class Label extends Element
{
    /**
     * Label constructor.
     *
     * @param $text
     */
    public function __construct($text)
    {
        parent::__construct('label');
        $this->text($text);
    }

    /**
     * Set the text for label.
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
     * Set what input this label is for.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $id
     * @return $this
     */
    public function for ($id)
    {
        $this->set('for', $id);

        return $this;
    }
}