<?php

require_once 'vendor/autoload.php';


//$input = (new \UMFlint\Html\Element('input'))->set('type', 'text')->set('value', 'testing testing blah')->set('class', 'form-control');

$input = (new \UMFlint\Html\Element('input'))->set([
    'type'  => 'text',
    'value' => 'sakjskj sadfsadf',
    'class' => 'form-control',
]);

$textarea = (new \UMFlint\Html\Element('textarea'))->appendChild('This is the data I have for an textarea.')->set('class', 'form-control');

echo $textarea . PHP_EOL;

$div = (new \UMFlint\Html\Element('div'))->set('class', 'form-group')
    ->appendChild((new \UMFlint\Html\Element('label'))->appendChild('Name')->set('class', 'control-label col-sm-2'))
    ->appendChild((new \UMFlint\Html\Element('div'))->set('class', 'col-sm-10')->appendChild((new \UMFlint\Html\Element('input'))->set([
        'type'        => 'text',
        'name'        => 'name',
        'placeholder' => 'Enter the name...',
    ])));

echo $div . PHP_EOL;