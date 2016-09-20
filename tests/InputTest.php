<?php

class InputTest extends \PHPUnit\Framework\TestCase
{
    public function testCheckbox()
    {
        $check = new \UMFlint\Html\Form\Input\Checkbox('check');
        $this->assertEquals('<input type="checkbox" name="check" id="check">', $check->render());

        // Test checking.
        $check->check();
        $this->assertEquals('checked', $check->get('checked'));

        // Test unchecking.
        $check->uncheck();
        $this->assertEquals(null, $check->get('checked'));

        // Test inline.
        $check->inline();
        $this->assertEquals(true, $check->isInline());
    }

    public function testCheckboxes()
    {
        $checks = new \UMFlint\Html\Form\Input\Checkboxes('checks');
        $this->assertEquals('<div type="checkboxes" name="checks" id="checks"></div>', $checks->render());
    }
}