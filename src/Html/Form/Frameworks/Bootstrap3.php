<?php

namespace UMFlint\Html\Form\Frameworks;

use UMFlint\Html\Element;
use UMFlint\Html\Form\Input\Input;

class Bootstrap3 implements Framework
{
    /**
     * @var Input
     */
    protected $input;

    /**
     * @var array
     */
    protected $config = [
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
    ];

    /**
     * Bootstrap3 constructor.
     *
     * @param Input $input
     */
    public function __construct(Input $input)
    {
        $this->input = $input;

        if (function_exists('config')) {
            $this->config = config('html.' . Bootstrap3::class);
        }
    }

    /**
     * Check if the field is one of certain types.
     *
     * @param  string|array $types
     * @return bool
     */
    protected function isType($types)
    {
        return in_array($this->input->getType(), (array)$types);
    }

    /**
     * Create the label for the input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Element
     */
    public function createLabel()
    {
        $label = new Element('label');
        $text = $this->input->getLabel();

        if (!$this->isType(['checkbox', 'radio'])) {
            $label->set('for', $this->input->getId())
                ->addClass($this->config['label']['class']);

            // Add column grid if horizontal.
            if ($this->config['form']['class'] == 'form-horizontal') {
                foreach ($this->config['label']['columns'] as $breakpoint => $width) {
                    if (!is_null($width)) {
                        $label->addClass("col-{$breakpoint}-{$width}");
                    }
                }
            }

            if ($this->input->isRequired()) {
                $text .= (new Element('sup'))->appendChild('*');
            }
        }

        $label->appendChild($text);

        return $label;
    }

    /**
     * Create the help block for the input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Element
     */
    public function createHelp()
    {
        if (!$this->input->hasHelp()) {
            return '';
        }

        return (new Element('span'))
            ->set('class', 'help-block')
            ->appendChild($this->input->getHelp());
    }

    /**
     * Create the validation errors list for the input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Element
     */
    public function createErrors()
    {
        if (!$this->input->hasErrors()) {
            return '';
        }

        $list = (new Element('ul'))->set('class', 'help-block');

        foreach ($this->input->getErrors() as $error) {
            $item = (new Element('li'))->appendChild($error);

            $list->appendChild($item);
        }

        return $list;
    }

    /**
     * Create input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    protected function createInput()
    {
        if (!$this->isType(['checkbox', 'radio', 'hidden', 'checkboxes', 'radios'])) {
            $this->input->addClass($this->config['input']['class']);
        }

        return $this->input->renderElement();
    }

    /**
     * Create the checkbox or radio input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    protected function createCheckboxOrRadio()
    {
        $class = $this->input->getType();

        if ($this->input->isInline()) {
            $wrapper = $this->createLabel()
                ->prependChild($this->createinput())
                ->addClass($class . '-inline');
        }else {
            $wrapper = (new Element('div'))
                ->addClass($class);

            $label = $this->createLabel()->prependChild($this->createinput());
            $wrapper->appendChild($label);
        }

        if ($this->input->hasErrors()) {
            $wrapper->addClass('has-error');
        }

        $wrapper->appendChild($this->createHelp());
        $wrapper->appendChild($this->createErrors());

        return $wrapper->render();
    }

    /**
     * Render the input group.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function render()
    {
        if ($this->isType('hidden')) {
            return $this->createInput();
        }

        if ($this->isType(['checkbox', 'radio'])) {
            if ($this->config['form']['class'] == 'form-horizontal' && $this->input->showLabel() && !$this->input->isInline()) {
                $wrapper = (new Element('div'))
                    ->addClass('form-group');
                $grid = new Element('div');

                // Set the offset.
                foreach ($this->config['label']['columns'] as $breakpoint => $width) {
                    if (!is_null($width)) {
                        $grid->addClass("col-{$breakpoint}-offset-{$width}");
                    }
                }

                // Set the input.
                foreach ($this->config['input']['columns'] as $breakpoint => $width) {
                    if (!is_null($width)) {
                        $grid->addClass("col-{$breakpoint}-{$width}");
                    }
                }

                $grid->appendChild($this->createCheckboxOrRadio());
                $wrapper->appendChild($grid);

                return $wrapper->render();
            }else {
                return $this->createCheckboxOrRadio();
            }
        }

        if ($this->config['form']['class'] == 'form-horizontal') {
            $wrapper = (new Element('div'))
                ->addClass('form-group');

            $grid = (new Element('div'));
            foreach ($this->config['input']['columns'] as $breakpoint => $width) {
                if (!is_null($width)) {
                    $grid->addClass("col-{$breakpoint}-{$width}");
                }
            }

            if ($this->input->showLabel()) {
                $wrapper->appendChild($this->createLabel());
            }else {
                foreach ($this->config['label']['columns'] as $breakpoint => $width) {
                    if (!is_null($width)) {
                        $grid->addClass("col-{$breakpoint}-offset-{$width}");
                    }
                }
            }

            if ($this->input->hasErrors()) {
                $wrapper->addClass('has-error');
            }

            $grid->appendChild($this->createinput());
            $grid->appendChild($this->createHelp());
            $grid->appendChild($this->createErrors());

            return $wrapper->appendChild($grid)->render();
        }elseif (is_null($this->config['form']['class']) || $this->config['form']['class'] == 'form-inline') {
            $wrapper = (new Element('div'))
                ->addClass('form-group');

            if ($this->input->showLabel()) {
                $wrapper->appendChild($this->createLabel());
            }

            if ($this->input->hasErrors()) {
                $wrapper->addClass('has-error');
            }

            $wrapper->appendChild($this->createinput());
            $wrapper->appendChild($this->createHelp());
            $wrapper->appendChild($this->createErrors());

            return $wrapper->render();
        }else {
            if ($this->input->hasErrors()) {
                $this->input->addClass('has-error');
            }

            $input = $this->createinput();
            $input .= $this->renderHelp();
            $input .= $this->renderErrors();

            return $input;
        }
    }
}
