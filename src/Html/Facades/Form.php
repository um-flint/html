<?php

namespace UMFlint\Html\Facades;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'umflint.html.form';
    }
}