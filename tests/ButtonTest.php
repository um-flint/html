<?php

class ButtonTest extends \PHPUnit\Framework\TestCase
{
    public function testRender()
    {
        $button = new \UMFlint\Html\Form\Button('Submit!');
        $this->assertEquals('<button type="submit">Submit!</button>', $button->render());
    }

    public function testText()
    {
        $button = new \UMFlint\Html\Form\Button('Submit!');
        $button->text('Reset');
        $this->assertEquals('<button type="submit">Reset</button>', $button->render());
    }

    public function testType()
    {
        $button = new \UMFlint\Html\Form\Button('Submit!');
        $button->type('delete');
        $this->assertEquals('<button type="delete">Submit!</button>', $button->render());
    }
}