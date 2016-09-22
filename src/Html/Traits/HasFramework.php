<?php

namespace UMFlint\Html\Traits;

use UMFlint\Html\Form\Frameworks\Bootstrap3;
use UMFlint\Html\Form\Frameworks\Framework;

trait HasFramework
{
    /**
     * @var Framework
     */
    protected $framework;

    /**
     * Default framework class.
     */
    protected static $FRAMEWORK = Bootstrap3::class;

    /**
     * Setup framework.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $framework
     * @throws \Exception
     */
    protected function setupFramework($framework)
    {
        $this->framework = new $framework();
        if (!$this->framework instanceof Framework) {
            throw new \Exception('Framework must be an instance of ' . Framework::class);
        }
    }
}