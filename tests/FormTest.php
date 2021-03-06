<?php

use UMFlint\Html\Form\Form;

class FormTest extends \PHPUnit\Framework\TestCase
{
    // Config for Form.
    protected $config = [
        'framework' => \UMFlint\Html\Form\Frameworks\Bootstrap3::class,
        'form'      => [
            'class' => 'form-horizontal', // form-horizontal || form-inline || null
        ],
        'label'     => [
            'class'   => 'control-label',
            'columns' => [
                'xs' => null,
                'sm' => 3,
                'md' => 3,
                'lg' => 3,
            ],
        ],
        'input'     => [
            'class'   => 'form-control',
            'columns' => [
                'xs' => 12,
                'sm' => 9,
                'md' => 9,
                'lg' => 9,
            ],
        ],
    ];

    public function testMethod()
    {
        // Test base rendering.
        $form = new Form($this->config);
        $this->assertEquals('<form></form>', $form->render());

        // Test actually setting method.
        $form->method('get');
        $this->assertEquals('GET', $form->get('method'));
        $form->method('delete');
        $this->assertEquals('POST', $form->get('method'));

        // Test setting an invalid method.
        try {
            $form->method('orbit');
        }catch (Exception $e) {
            $this->assertEquals('Invalid method', $e->getMessage());
        }
    }

    public function testOpen()
    {
        $form = new Form($this->config);
        $form->open('test.store');
        $this->assertEquals('test.store', $form->get('action'));
    }

    public function testHasFiles()
    {
        $form = new Form($this->config);
        $form->hasFiles();
        $this->assertEquals('multipart/form-data', $form->get('enctype'));
    }

    public function testCallable()
    {
        $form = new Form($this->config);

        $checkbox = $form->checkbox('checkbox');
        $this->assertTrue($checkbox instanceof \UMFlint\Html\Form\Input\Checkbox);

        $color = $form->color('color');
        $this->assertTrue($color instanceof \UMFlint\Html\Form\Input\Color);

        $date = $form->date('date');
        $this->assertTrue($date instanceof \UMFlint\Html\Form\Input\Date);

        $datetime = $form->datetime('datetime');
        $this->assertTrue($datetime instanceof \UMFlint\Html\Form\Input\Datetime);

        $email = $form->email('email');
        $this->assertTrue($email instanceof \UMFlint\Html\Form\Input\Email);

        $file = $form->file('file');
        $this->assertTrue($file instanceof \UMFlint\Html\Form\Input\File);

        $hidden = $form->hidden('hidden');
        $this->assertTrue($hidden instanceof \UMFlint\Html\Form\Input\Hidden);

        $image = $form->image('image');
        $this->assertTrue($image instanceof \UMFlint\Html\Form\Input\Image);

        $month = $form->month('month');
        $this->assertTrue($month instanceof \UMFlint\Html\Form\Input\Month);

        $number = $form->number('number');
        $this->assertTrue($number instanceof \UMFlint\Html\Form\Input\Number);

        $password = $form->password('password');
        $this->assertTrue($password instanceof \UMFlint\Html\Form\Input\Password);

        $radio = $form->radio('radio');
        $this->assertTrue($radio instanceof \UMFlint\Html\Form\Input\Radio);

        $range = $form->range('range');
        $this->assertTrue($range instanceof \UMFlint\Html\Form\Input\Range);

        $search = $form->search('search');
        $this->assertTrue($search instanceof \UMFlint\Html\Form\Input\Search);

        $select = $form->select('select');
        $this->assertTrue($select instanceof \UMFlint\Html\Form\Input\Select);

        $tel = $form->tel('tel');
        $this->assertTrue($tel instanceof \UMFlint\Html\Form\Input\Tel);

        $text = $form->text('text');
        $this->assertTrue($text instanceof \UMFlint\Html\Form\Input\Text);

        $textarea = $form->textarea('textarea');
        $this->assertTrue($textarea instanceof \UMFlint\Html\Form\Input\Textarea);

        $time = $form->time('time');
        $this->assertTrue($time instanceof \UMFlint\Html\Form\Input\Time);

        $url = $form->url('url');
        $this->assertTrue($url instanceof \UMFlint\Html\Form\Input\Url);

        $week = $form->week('week');
        $this->assertTrue($week instanceof \UMFlint\Html\Form\Input\Week);

        $blah = $form->blah('blah');
        $this->assertNull($blah);
    }

    public function testOldPopulate()
    {
        $form = new Form($this->config);
        $form->old([
            'name' => 'Bob Doe',
        ]);
        $form->populate('');
        $text = $form->text('name');
        $this->assertEquals('<input type="text" name="name" id="name" value="Bob Doe" class="form-control">', $text->render());
    }

    public function testButton()
    {
        $form = new Form($this->config);
        $this->assertEquals('<button type="submit">Submit Your Form</button>', $form->button('Submit Your Form')->render());
    }

    public function testLabel()
    {
        $form = new Form($this->config);
        $label = $form->label('this-is-a-label');
        $text = $label->getChildren()[0];
        $this->assertEquals('this-is-a-label', $text);
    }

    public function testClose()
    {
        $form = new Form($this->config);
        $this->assertEquals('</form>', $form->close());
    }
}