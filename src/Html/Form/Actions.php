<?php

namespace UMFlint\Html\Form;

use UMFlint\Html\Element;
use UMFlint\Html\Traits\HasFramework;

class Actions extends Element
{
    use HasFramework;

    /**
     * Actions constructor.
     *
     * @param null $children
     * @param null $framework
     */
    public function __construct($children = null, $framework = null)
    {
        parent::__construct('div');

        if (!is_null($children)) {
            $this->appendChild($children);
        }

        if (is_null($framework)) {
            $framework = self::$FRAMEWORK;
        }
        $this->setupFramework($framework);

        return $this;
    }

    /**
     * Render the element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function renderElement()
    {
        return parent::render();
    }

    /**
     * Render this.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function render()
    {
        return $this->framework->renderActions($this);
    }
}