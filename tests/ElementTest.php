<?php

use \UMFlint\Html\Element;

class ElementTest extends \PHPUnit\Framework\TestCase
{
    public function testGetType()
    {
        $div = new Element('div');

        $this->assertEquals('div', $div->getTag());
    }

    public function testRender()
    {
        $div = new Element('div');
        $this->assertEquals('<div></div>', $div->render());
    }

    public function testRenderChildren()
    {
        $div = new Element('div');
        $div->appendChild('text');

        $this->assertEquals('<div>text</div>', $div->render());
    }

    public function testIsVoid()
    {
        // Is void.
        $br = new Element('br');
        $this->assertEquals(1, $br->isVoid());

        // Is not void.
        $div = new Element('div');
        $this->assertNotEquals(1, $div->isVoid());
    }

    public function testAddRemoveClass()
    {
        $div = new Element('div');

        // Add.
        $div->addClass('button');
        preg_match('/class="(.*?)"/', $div->render(), $classes);
        $this->assertEquals('button', $classes[1]);

        // Add Multiple.
        $div->addClass(['foo', 'bar']);
        preg_match('/class="(.*?)"/', $div->render(), $classes);
        $this->assertEquals('button foo bar', $classes[1]);

        // Remove.
        $div->removeClass('button');
        preg_match('/class="(.*?)"/', $div->render(), $classes);
        $this->assertEquals('foo bar', $classes[1]);

        // Remove Multiple
        $div->removeClass(['foo', 'bar']);
        preg_match('/class="(.*?)"/', $div->render(), $classes);
        $this->assertEquals('', $classes[1]);
    }

    public function testGetAppendPrependEmptyChildren()
    {
        $div = new Element('div');

        // Append.
        $div->appendChild('text');
        $this->assertEquals('text', $div->getChildren()[0]);

        // Prepend.
        $div->prependChild('NEW');
        $this->assertEquals('NEW', $div->getChildren()[0]);

        // Empty.
        $div->emptyChildren();
        $this->assertEmpty($div->getChildren());
    }
}