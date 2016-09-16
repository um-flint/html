<?php

namespace UMFlint\Html\Traits;

trait Inlineable
{
    /**
     * @var bool
     */
    protected $inline = false;

    /**
     * Set whether inline or not.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $inline
     * @return $this
     */
    public function inline($inline = true)
    {
        if ($inline) {
            $this->inline = true;
        }else {
            $this->inline = false;
        }

        return $this;
    }

    /**
     * Check whether inline or not.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return bool
     */
    public function isInline()
    {
        return $this->inline;
    }
}