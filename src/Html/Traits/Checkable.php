<?php

namespace UMFlint\Html\Traits;

trait Checkable
{
    /**
     * Helper to check an input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return $this
     */
    public function check()
    {
        $this->set('checked', 'checked');

        return $this;
    }

    /**
     * Helper to uncheck an input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return $this
     */
    public function uncheck()
    {
        $this->remove('checked');

        return $this;
    }
}