<?php

use UMFlint\Html\Form\Input\Input;

class InputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Testing the base input class.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function testInput()
    {
        // General.
        $input = new Input('name');
        $this->assertEquals(null, $input->getType());
        $this->assertEquals('name', $input->get('name'));
        $this->assertEquals('name', $input->getId());

        $input->value('notInput');
        $this->assertEquals('notInput', $input->get('value'));
        $this->assertEquals('<input name="name" id="name" value="notInput">', $input->render());

        // Test auto labeling.
        // Dot notation.
        $testAutoLabel = new Input('hello.this.is.a.test');
        $this->assertEquals('<input name="hello[this][is][a][test]" id="hello[this][is][a][test]">', $testAutoLabel->render());
        // Bracket notation.
        $testAutoLabel = new Input('users[]');
        $this->assertEquals('<input name="users[]" id="users[]">', $testAutoLabel->render());

        // Required testing.
        $this->assertEquals(false, $input->isRequired());

        // Test setting required to true too.
        $input->set('required', 'required');
        $this->assertEquals(true, $input->isRequired());
    }

    /**
     * Testing the base input rules.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function testInputRules()
    {
        // Regex
        $input = new Input('inputRules');
        $input->rules('regex:/^.+\/+$/');
        $this->assertEquals('^.+\/+$', $input->get('pattern'));

        // Accepted.
        $input = new Input('inputRules');
        $input->rules('accepted');
        $this->assertEquals('required', $input->get('required'));

        // Active URL.
        $input = new Input('inputRules');
        $input->rules('active_url');
        $input->value('umflint.edu');
        $this->assertEquals(true, checkdnsrr($input->get('value'), 'ANY'));

        // After date.
        $input = new Input('inputRules');
        $input->rules('after:2016-09-20');
        $this->assertEquals('2016-09-20', $input->get('min'));

        // Alpha.
        $input = new Input('inputRules');
        $input->rules('alpha');
        $this->assertEquals('[a-zA-Z]+', $input->get('pattern'));

        // Alpha dash.
        $input = new Input('inputRules');
        $input->rules('alpha_dash');
        $this->assertEquals('[a-zA-Z0-9_\-]+', $input->get('pattern'));

        // Alpha num.
        $input = new Input('inputRules');
        $input->rules('alpha_num');
        $this->assertEquals('[a-zA-Z0-9]+', $input->get('pattern'));

        // Before date.
        $input = new Input('inputRules');
        $input->rules('before:2016-09-15');
        $this->assertEquals('2016-09-15', $input->get('max'));

        // Between.
        $input = new Input('inputRules');
        $input->rules('between:2,5');
        $this->assertEquals('2', $input->get('minlength'));
        $this->assertEquals('5', $input->get('maxlength'));

        // Boolean.
        $input = new Input('inputRules');
        $input->rules('boolean');
        $this->assertEquals('0|1', $input->get('pattern'));

        // Digit.
        $input = new Input('inputRules');
        $input->rules('digits:5');
        $this->assertEquals('\d{5}', $input->get('pattern'));

        // Digits between.
        $input = new Input('inputRules');
        $input->rules('digits_between:0,9');
        $this->assertEquals('\d{0,9}', $input->get('pattern'));

        // Image.
        $input = new Input('inputRules');
        $input->rules('image');
        $this->assertEquals('image/*', $input->get('accept'));

        // In.
        $input = new Input('inputRules');
        $input->rules('in:foo,bar');
        $this->assertEquals('foo|bar', $input->get('pattern'));

        // Not in.
        $input = new Input('inputRules');
        $input->rules('not_in:foo,bar');
        $this->assertEquals('(?:(?!^foo$|^bar$).)*', $input->get('pattern'));

        // Integer.
        $input = new Input('inputRules');
        $input->rules('integer');
        $this->assertEquals('\d+', $input->get('pattern'));

        // Max value.
        $input = new Input('inputRules');
        $input->rules('max:500');
        $this->assertEquals('500', $input->get('maxlength'));

        // Min.
        $input = new Input('inputRules');
        $input->rules('min:256');
        $this->assertEquals('256', $input->get('minlength'));

        // Mimes.
        $input = new Input('inputRules');
        $input->rules('mimes:jpeg,bmp,gif,png');
        $this->assertEquals('.jpeg, .bmp, .gif, .png', $input->get('accept'));

        // Numeric.
        $input = new Input('inputRules');
        $input->rules('numeric');
        $this->assertEquals('[-+]?[0-9]*[.,]?[0-9]+', $input->get('pattern'));

        // Required.
        $input = new Input('inputRules');
        $input->rules('required');
        $this->assertEquals('required', $input->get('required'));

        // Size.
        $input = new Input('inputRules');
        $input->rules('size:500');
        $this->assertEquals('.{500}', $input->get('pattern'));
    }

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
    }

    public function testColor()
    {
        $color = new \UMFlint\Html\Form\Input\Color('purple');
        $this->assertEquals('color', $color->getType());
        $this->assertEquals('<input type="color" name="purple" id="purple">', $color->render());
    }

    public function testDate()
    {
        $date = new \UMFlint\Html\Form\Input\Date('date');
        $this->assertEquals('date', $date->getType());
        $this->assertEquals('<input type="date" name="date" id="date">', $date->render());
    }

    public function testDateTime()
    {
        $dt = new \UMFlint\Html\Form\Input\Datetime('datetime');
        $this->assertEquals('datetime', $dt->getType());
        $this->assertEquals('<input type="datetime" name="datetime" id="datetime">', $dt->render());
    }

    public function testEmail()
    {
        $email = new \UMFlint\Html\Form\Input\Email('email');
        $this->assertEquals('email', $email->getType());
        $this->assertEquals('<input type="email" name="email" id="email">', $email->render());
    }

    public function testFile()
    {
        $file = new \UMFlint\Html\Form\Input\File('file');
        $this->assertEquals('file', $file->getType());
        $this->assertEquals('<input type="file" name="file" id="file">', $file->render());
    }

    public function testHidden()
    {
        $hidden = new \UMFlint\Html\Form\Input\Hidden('hidden');
        $this->assertEquals('hidden', $hidden->getType());
        $this->assertEquals('<input type="hidden" name="hidden" id="hidden">', $hidden->render());
    }

    public function testImage()
    {
        $image = new \UMFlint\Html\Form\Input\Image('image');
        $this->assertEquals('image', $image->getType());
        $this->assertEquals('<input type="image" name="image" id="image">', $image->render());
    }

    public function testMonth()
    {
        $month = new \UMFlint\Html\Form\Input\Month('month');
        $this->assertEquals('month', $month->getType());
        $this->assertEquals('<input type="month" name="month" id="month">', $month->render());
    }

    public function testNumber()
    {
        $number = new \UMFlint\Html\Form\Input\Number('number');
        $this->assertEquals('number', $number->getType());
        $this->assertEquals('<input type="number" name="number" id="number">', $number->render());
    }

    public function testPassword()
    {
        $password = new \UMFlint\Html\Form\Input\Password('password');
        $this->assertEquals('password', $password->getType());
        $this->assertEquals('<input type="password" name="password" id="password">', $password->render());
    }

    public function testRadio()
    {
        $radio = new \UMFlint\Html\Form\Input\Radio('radio');
        $this->assertEquals('radio', $radio->getType());
        $this->assertEquals('<input type="radio" name="radio" id="radio">', $radio->render());
    }

    public function testRange()
    {
        $range = new \UMFlint\Html\Form\Input\Range('range');
        $this->assertEquals('range', $range->getType());
        $this->assertEquals('<input type="range" name="range" id="range">', $range->render());
    }

    public function testSearch()
    {
        $search = new \UMFlint\Html\Form\Input\Search('search');
        $this->assertEquals('search', $search->getType());
        $this->assertEquals('<input type="search" name="search" id="search">', $search->render());
    }

    public function testSelect()
    {
        $options = [
            '1' => 'Tyler Elton',
            '2' => 'Donald Wilcox',
            '3' => 'John Doe',
        ];
        $select = new \UMFlint\Html\Form\Input\Select('names', 'abc');
        $select->options($options);
        $this->assertEquals('<select name="names" id="names"><option value="1">Tyler Elton</option><option value="2">Donald Wilcox</option><option value="3">John Doe</option></select>', $select->render());

        // Single select.
        $select->value(1);
        $this->assertEquals('<select name="names" id="names"><option value="1" selected="selected">Tyler Elton</option><option value="2">Donald Wilcox</option><option value="3">John Doe</option></select>', $select->render());

        // Multiple select.
        $select->multiple();
        $this->assertEquals('<select name="names[]" id="names[]" multiple="multiple"><option value="1" selected="selected">Tyler Elton</option><option value="2">Donald Wilcox</option><option value="3">John Doe</option></select>', $select->render());

        $select->value(1);
        $select->value(2);
        $this->assertEquals('<select name="names[]" id="names[]" multiple="multiple"><option value="1">Tyler Elton</option><option value="2" selected="selected">Donald Wilcox</option><option value="3">John Doe</option></select>', $select->render());
    }

    public function testTel()
    {
        $tel = new \UMFlint\Html\Form\Input\Tel('tel');
        $this->assertEquals('tel', $tel->getType());
        $this->assertEquals('<input type="tel" name="tel" id="tel">', $tel->render());
    }

    public function testText()
    {
        $text = new \UMFlint\Html\Form\Input\Text('text');
        $this->assertEquals('text', $text->getType());
        $this->assertEquals('<input type="text" name="text" id="text">', $text->render());
    }

    public function testTime()
    {
        $time = new \UMFlint\Html\Form\Input\Time('time');
        $this->assertEquals('time', $time->getType());
        $this->assertEquals('<input type="time" name="time" id="time">', $time->render());
    }

    public function testUrl()
    {
        $url = new \UMFlint\Html\Form\Input\Url('url');
        $this->assertEquals('url', $url->getType());
        $this->assertEquals('<input type="url" name="url" id="url">', $url->render());
    }

    public function testWeek()
    {
        $week = new \UMFlint\Html\Form\Input\Week('week');
        $this->assertEquals('week', $week->getType());
        $this->assertEquals('<input type="week" name="week" id="week">', $week->render());
    }
}