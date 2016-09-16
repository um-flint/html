<?php

namespace UMFlint\Html\Form\Input;

class Hidden extends Input
{
    protected $inputType = 'hidden';

    /**
     * Hidden input does not have a label.
     *
     * @var bool
     */
    protected $showLabel = false;
}