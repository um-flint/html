<?php

namespace UMFlint\Html\Form\Frameworks;

interface Framework
{
    /**
     * Create a label for inputs.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function createLabel();

    /**
     * Create the help block.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function createHelp();

    /**
     * Create the errors list.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function createErrors();

    /**
     * Render the entire input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function render();
}