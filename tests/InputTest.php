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
        // For error testing.
        $errors = [
            'msg' => 'This is an error. It is for testing.',
        ];

        // General.
        $input = new Input('name');
        $this->assertEquals(null, $input->getType());
        $this->assertEquals('name', $input->get('name'));
        $this->assertEquals('name', $input->getId());
        $this->assertEquals('Name', $input->getLabel());
        $this->assertEquals(true, $input->showLabel());

        // Set showLabel to false.
        $input->showLabel(false);
        $this->assertEquals(false, $input->showLabel());
        // Set it back to true for testing.
        $input->showLabel(true);

        $input->value('notInput');
        $this->assertEquals('notInput', $input->get('value'));
        $this->assertEquals('<div class="form-group"><label for="name" class="control-label col-sm-3 col-md-3 col-lg-3">Name</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input name="name" id="name" value="notInput" class="form-control"></div></div>', $input->render());

        // Test auto labeling.
        // Dot notation.
        $testAutoLabel = new Input('hello.this.is.a.test');
        $this->assertEquals('<div class="form-group"><label for="hello[this][is][a][test]" class="control-label col-sm-3 col-md-3 col-lg-3">Hello This Is A Test</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input name="hello[this][is][a][test]" id="hello[this][is][a][test]" class="form-control"></div></div>', $testAutoLabel->render());
        // Bracket notation.
        $testAutoLabel = new Input('users[]');
        $this->assertEquals('<div class="form-group"><label for="users[]" class="control-label col-sm-3 col-md-3 col-lg-3">Users</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input name="users[]" id="users[]" class="form-control"></div></div>', $testAutoLabel->render());

        // Error testing.
        $this->assertEquals(false, $input->hasErrors());
        $input->setErrors($errors);
        $this->assertEquals('This is an error. It is for testing.', $input->getErrors($errors)['msg']);
        $this->assertEquals(true, $input->hasErrors());

        // Help testing.
        $this->assertEquals(false, $input->hasHelp());
        $input->help('Help me.');
        $this->assertEquals('Help me.', $input->getHelp());
        $this->assertEquals(true, $input->hasHelp());

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
        $this->assertEquals('<div class="form-group"><div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-12 col-sm-9 col-md-9 col-lg-9"><div class="checkbox"><label><input type="checkbox" name="check" id="check">Check</label></div></div></div>', $check->render());

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
        $this->assertEquals('<div class="form-group"><label for="checks" class="control-label col-sm-3 col-md-3 col-lg-3">Checks</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="checkboxes" name="checks" id="checks"></div></div></div>', $checks->render());

        $items = [
            [
                'label' => 'telton',
                'name'  => 'test',
                'value' => 'Tyler Elton',
            ],
            [
                'label' => 'dowilcox',
                'name'  => 'text',
                'value' => 'Donald Wilcox',
            ],
        ];
        $checks->checkboxes($items);
        $this->assertEquals('<div class="form-group"><label for="checks" class="control-label col-sm-3 col-md-3 col-lg-3">Checks</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="checkboxes" name="checks" id="checks"><div class="checkbox"><label><input type="checkbox" name="checks[test]" value="Tyler Elton">telton</label></div><div class="checkbox"><label><input type="checkbox" name="checks[text]" value="Donald Wilcox">dowilcox</label></div></div></div></div>',
            $checks->render());

        // Test inline.
        $checks->inline();
        $this->assertEquals(true, $checks->isInline());
        $this->assertEquals('<div class="form-group"><label for="checks" class="control-label col-sm-3 col-md-3 col-lg-3">Checks</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="checkboxes" name="checks" id="checks"><label class="checkbox-inline"><input type="checkbox" name="checks[test]" value="Tyler Elton">telton</label><label class="checkbox-inline"><input type="checkbox" name="checks[text]" value="Donald Wilcox">dowilcox</label></div></div></div>', $checks->render());

        // Set inline false.
        $checks->inline(false);
        $this->assertEquals('<div class="form-group"><label for="checks" class="control-label col-sm-3 col-md-3 col-lg-3">Checks</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="checkboxes" name="checks" id="checks"><div class="checkbox"><label><input type="checkbox" name="checks[test]" value="Tyler Elton">telton</label></div><div class="checkbox"><label><input type="checkbox" name="checks[text]" value="Donald Wilcox">dowilcox</label></div></div></div></div>',
            $checks->render());

        // Test checking.
        $items = [
            [
                'label' => 'telton',
                'name'  => 'test',
                'value' => 'Tyler Elton',
            ],
            [
                'label' => 'dowilcox',
                'name'  => 'text',
                'value' => 'Donald Wilcox',
            ],
        ];
        $checkboxes = new \UMFlint\Html\Form\Input\Checkboxes('checkboxes');
        $checkboxes->value([
            'Tyler Elton',
        ]);
        $checkboxes->checkboxes($items);
        $this->assertEquals('<div class="form-group"><label for="checkboxes" class="control-label col-sm-3 col-md-3 col-lg-3">Checkboxes</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="checkboxes" name="checkboxes" id="checkboxes"><div class="checkbox"><label><input type="checkbox" name="checkboxes[test]" value="Tyler Elton" checked="checked">telton</label></div><div class="checkbox"><label><input type="checkbox" name="checkboxes[text]" value="Donald Wilcox">dowilcox</label></div></div></div></div>',
            $checkboxes->render());
    }

    public function testColor()
    {
        $color = new \UMFlint\Html\Form\Input\Color('purple');
        $this->assertEquals('color', $color->getType());
        $this->assertEquals('<div class="form-group"><label for="purple" class="control-label col-sm-3 col-md-3 col-lg-3">Purple</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="color" name="purple" id="purple" class="form-control"></div></div>', $color->render());
    }

    public function testDate()
    {
        $date = new \UMFlint\Html\Form\Input\Date('date');
        $this->assertEquals('date', $date->getType());
        $this->assertEquals('<div class="form-group"><label for="date" class="control-label col-sm-3 col-md-3 col-lg-3">Date</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="date" name="date" id="date" class="form-control"></div></div>', $date->render());
    }

    public function testDateTime()
    {
        $dt = new \UMFlint\Html\Form\Input\Datetime('datetime');
        $this->assertEquals('datetime', $dt->getType());
        $this->assertEquals('<div class="form-group"><label for="datetime" class="control-label col-sm-3 col-md-3 col-lg-3">Datetime</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="datetime" name="datetime" id="datetime" class="form-control"></div></div>', $dt->render());
    }

    public function testEmail()
    {
        $email = new \UMFlint\Html\Form\Input\Email('email');
        $this->assertEquals('email', $email->getType());
        $this->assertEquals('<div class="form-group"><label for="email" class="control-label col-sm-3 col-md-3 col-lg-3">Email</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="email" name="email" id="email" class="form-control"></div></div>', $email->render());
    }

    public function testFile()
    {
        $file = new \UMFlint\Html\Form\Input\File('file');
        $this->assertEquals('file', $file->getType());
        $this->assertEquals('<div class="form-group"><label for="file" class="control-label col-sm-3 col-md-3 col-lg-3">File</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="file" name="file" id="file" class="form-control"></div></div>', $file->render());
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
        $this->assertEquals('<div class="form-group"><label for="image" class="control-label col-sm-3 col-md-3 col-lg-3">Image</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="image" name="image" id="image" class="form-control"></div></div>', $image->render());
    }

    public function testMonth()
    {
        $month = new \UMFlint\Html\Form\Input\Month('month');
        $this->assertEquals('month', $month->getType());
        $this->assertEquals('<div class="form-group"><label for="month" class="control-label col-sm-3 col-md-3 col-lg-3">Month</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="month" name="month" id="month" class="form-control"></div></div>', $month->render());
    }

    public function testNumber()
    {
        $number = new \UMFlint\Html\Form\Input\Number('number');
        $this->assertEquals('number', $number->getType());
        $this->assertEquals('<div class="form-group"><label for="number" class="control-label col-sm-3 col-md-3 col-lg-3">Number</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="number" name="number" id="number" class="form-control"></div></div>', $number->render());
    }

    public function testPassword()
    {
        $password = new \UMFlint\Html\Form\Input\Password('password');
        $this->assertEquals('password', $password->getType());
        $this->assertEquals('<div class="form-group"><label for="password" class="control-label col-sm-3 col-md-3 col-lg-3">Password</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="password" name="password" id="password" class="form-control"></div></div>', $password->render());
    }

    public function testRadio()
    {
        $radio = new \UMFlint\Html\Form\Input\Radio('radio');
        $this->assertEquals('radio', $radio->getType());
        $this->assertEquals('<div class="form-group"><div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-12 col-sm-9 col-md-9 col-lg-9"><div class="radio"><label><input type="radio" name="radio" id="radio">Radio</label></div></div></div>', $radio->render());
    }

    public function testRadios()
    {
        $radios = new \UMFlint\Html\Form\Input\Radios('radios');
        $this->assertEquals('<div class="form-group"><label for="radios" class="control-label col-sm-3 col-md-3 col-lg-3">Radios</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="radios" name="radios" id="radios"></div></div></div>', $radios->render());

        $items = [
            [
                'label' => 'telton',
                'name'  => 'test',
                'value' => 'Tyler Elton',
            ],
            [
                'label' => 'dowilcox',
                'name'  => 'text',
                'value' => 'Donald Wilcox',
            ],
        ];
        $radios->radios($items);
        $this->assertEquals('<div class="form-group"><label for="radios" class="control-label col-sm-3 col-md-3 col-lg-3">Radios</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="radios" name="radios" id="radios"><div class="radio"><label><input type="radio" name="test" value="Tyler Elton">telton</label></div><div class="radio"><label><input type="radio" name="text" value="Donald Wilcox">dowilcox</label></div></div></div></div>', $radios->render());

        // Test inline.
        $radios->inline();
        $this->assertEquals(true, $radios->isInline());
        $this->assertEquals('<div class="form-group"><label for="radios" class="control-label col-sm-3 col-md-3 col-lg-3">Radios</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="radios" name="radios" id="radios"><label class="radio-inline"><input type="radio" name="test" value="Tyler Elton">telton</label><label class="radio-inline"><input type="radio" name="text" value="Donald Wilcox">dowilcox</label></div></div></div>', $radios->render());

        // Set inline false.
        $radios->inline(false);
        $this->assertEquals(false, $radios->isInline());
        $this->assertEquals('<div class="form-group"><label for="radios" class="control-label col-sm-3 col-md-3 col-lg-3">Radios</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="radios" name="radios" id="radios"><div class="radio"><label><input type="radio" name="test" value="Tyler Elton">telton</label></div><div class="radio"><label><input type="radio" name="text" value="Donald Wilcox">dowilcox</label></div></div></div></div>', $radios->render());

        // Test checking.
        $items = [
            [
                'label' => 'telton',
                'name'  => 'test',
                'value' => 'Tyler Elton',
            ],
            [
                'label' => 'dowilcox',
                'name'  => 'text',
                'value' => 'Donald Wilcox',
            ],
        ];
        $radios = new \UMFlint\Html\Form\Input\Radios('radios');
        $radios->value([
            'Tyler Elton',
        ]);
        $radios->radios($items);
        $this->assertEquals('<div class="form-group"><label for="radios" class="control-label col-sm-3 col-md-3 col-lg-3">Radios</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><div type="radios" name="radios" id="radios"><div class="radio"><label><input type="radio" name="test" value="Tyler Elton" checked="checked">telton</label></div><div class="radio"><label><input type="radio" name="text" value="Donald Wilcox">dowilcox</label></div></div></div></div>', $radios->render());
    }

    public function testRange()
    {
        $range = new \UMFlint\Html\Form\Input\Range('range');
        $this->assertEquals('range', $range->getType());
        $this->assertEquals('<div class="form-group"><label for="range" class="control-label col-sm-3 col-md-3 col-lg-3">Range</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="range" name="range" id="range" class="form-control"></div></div>', $range->render());
    }

    public function testSearch()
    {
        $search = new \UMFlint\Html\Form\Input\Search('search');
        $this->assertEquals('search', $search->getType());
        $this->assertEquals('<div class="form-group"><label for="search" class="control-label col-sm-3 col-md-3 col-lg-3">Search</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="search" name="search" id="search" class="form-control"></div></div>', $search->render());
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
        $this->assertEquals('<div class="form-group"><label for="names" class="control-label col-sm-3 col-md-3 col-lg-3">Names</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><select name="names" id="names" class="form-control"><option value="1">Tyler Elton</option><option value="2">Donald Wilcox</option><option value="3">John Doe</option></select></div></div>', $select->render());

        // Single select.
        $select->value(1);
        $this->assertEquals('<div class="form-group"><label for="names" class="control-label col-sm-3 col-md-3 col-lg-3">Names</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><select name="names" id="names" class="form-control"><option value="1" selected="selected">Tyler Elton</option><option value="2">Donald Wilcox</option><option value="3">John Doe</option></select></div></div>', $select->render());

        // Multiple select.
        $select->multiple();
        $this->assertEquals('<div class="form-group"><label for="names[]" class="control-label col-sm-3 col-md-3 col-lg-3">Names</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><select name="names[]" id="names[]" class="form-control" multiple="multiple"><option value="1" selected="selected">Tyler Elton</option><option value="2">Donald Wilcox</option><option value="3">John Doe</option></select></div></div>', $select->render());

        $select->value(1);
        $select->value(2);
        $this->assertEquals('<div class="form-group"><label for="names[]" class="control-label col-sm-3 col-md-3 col-lg-3">Names</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><select name="names[]" id="names[]" class="form-control" multiple="multiple"><option value="1">Tyler Elton</option><option value="2" selected="selected">Donald Wilcox</option><option value="3">John Doe</option></select></div></div>', $select->render());
    }

    public function testTel()
    {
        $tel = new \UMFlint\Html\Form\Input\Tel('tel');
        $this->assertEquals('tel', $tel->getType());
        $this->assertEquals('<div class="form-group"><label for="tel" class="control-label col-sm-3 col-md-3 col-lg-3">Tel</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="tel" name="tel" id="tel" class="form-control"></div></div>', $tel->render());
    }

    public function testText()
    {
        $text = new \UMFlint\Html\Form\Input\Text('text');
        $this->assertEquals('text', $text->getType());
        $this->assertEquals('<div class="form-group"><label for="text" class="control-label col-sm-3 col-md-3 col-lg-3">Text</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="text" name="text" id="text" class="form-control"></div></div>', $text->render());
    }

    public function testTime()
    {
        $time = new \UMFlint\Html\Form\Input\Time('time');
        $this->assertEquals('time', $time->getType());
        $this->assertEquals('<div class="form-group"><label for="time" class="control-label col-sm-3 col-md-3 col-lg-3">Time</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="time" name="time" id="time" class="form-control"></div></div>', $time->render());
    }

    public function testUrl()
    {
        $url = new \UMFlint\Html\Form\Input\Url('url');
        $this->assertEquals('url', $url->getType());
        $this->assertEquals('<div class="form-group"><label for="url" class="control-label col-sm-3 col-md-3 col-lg-3">Url</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="url" name="url" id="url" class="form-control"></div></div>', $url->render());
    }

    public function testWeek()
    {
        $week = new \UMFlint\Html\Form\Input\Week('week');
        $this->assertEquals('week', $week->getType());
        $this->assertEquals('<div class="form-group"><label for="week" class="control-label col-sm-3 col-md-3 col-lg-3">Week</label><div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"><input type="week" name="week" id="week" class="form-control"></div></div>', $week->render());
    }
}