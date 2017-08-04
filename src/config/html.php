<?php

return [
    'framework'                                     => \UMFlint\Html\Form\Frameworks\Bootstrap3::class,
    \UMFlint\Html\Form\Frameworks\Bootstrap3::class => [
        'form'  => [
            'class' => 'form-horizontal', // form-horizontal || form-inline || null
        ],
        'label' => [
            'class'   => 'control-label',
            'columns' => [
                'xs' => null,
                'sm' => 3,
                'md' => 3,
                'lg' => 3,
            ],
        ],
        'input' => [
            'class'   => 'form-control',
            'columns' => [
                'xs' => 12,
                'sm' => 9,
                'md' => 9,
                'lg' => 9,
            ],
        ],
    ],
];