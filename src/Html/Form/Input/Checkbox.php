<?php

namespace UMFlint\Html\Form\Input;

use UMFlint\Html\Traits\Checkable;
use UMFlint\Html\Traits\Inlineable;

class Checkbox extends Input
{
    use Checkable;

    protected $type = 'checkbox';
}