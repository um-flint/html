<?php

namespace UMFlint\Html\Form\Frameworks;

use UMFlint\Html\Form\Actions;
use UMFlint\Html\Form\Input\Input;

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
     * @param Input $input
     * @return string
     */
    public function render(Input $input);

    /**
     * Render actions.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param Actions $actions
     * @return mixed
     */
    public function renderActions(Actions $actions);
}