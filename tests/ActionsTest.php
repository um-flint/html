<?php

class ActionsTest extends \PHPUnit\Framework\TestCase
{
    // Config for Form.
    protected $config = [
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

    public function testRender()
    {
        $action = new \UMFlint\Html\Form\Actions();
        $this->assertEquals('<div class="form-group"><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-offset-3 col-md-offset-3 col-lg-offset-3"></div></div>', $action->render());

        $form = new \UMFlint\Html\Form\Form($this->config);
        $submitButton = $form->actions($form->button('Submit!')->set('class', 'btn btn-primary'), $form->button('Submit!', 'reset')->set('class', 'btn btn-primary'));

        $this->assertEquals('<div class="form-group"><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-offset-3 col-md-offset-3 col-lg-offset-3"><button type="submit" class="btn btn-primary">Submit!</button></div></div>', $submitButton->render());
    }
}