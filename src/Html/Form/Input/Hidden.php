<?php

namespace UMFlint\Html\Form\Input;

class Hidden extends Input
{
    protected $type = 'hidden';

    /**
     * Hidden input does not have a label.
     *
     * @var bool
     */
    protected $showLabel = false;
}