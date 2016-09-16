<?php

namespace UMFlint\Html\Form\Input;

use UMFlint\Html\Traits\Checkable;
use UMFlint\Html\Traits\Inlineable;

class Radio extends Input
{
    use Checkable, Inlineable;

    protected $inputType = 'radio';
}