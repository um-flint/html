<?php

class ActionsTest extends \PHPUnit\Framework\TestCase
{
    public function testRender()
    {
        $action = new \UMFlint\Html\Form\Actions();
        $this->assertEquals('<div class="form-group"><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-offset-3 col-md-offset-3 col-lg-offset-3"></div></div>', $action->render());

        $button = new \UMFlint\Html\Form\Button('Submit!');
        $action = new \UMFlint\Html\Form\Actions($button->set('class', 'btn btn-primary'));

        $this->assertEquals('<div class="form-group"><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-offset-3 col-md-offset-3 col-lg-offset-3"><button type="submit" class="btn btn-primary">Submit!</button></div></div>', $action->render());
    }
}