<?php

namespace UMFlint\Html\Form\Input;

use UMFlint\Html\Element;
use UMFlint\Html\Form\Frameworks\Bootstrap3;
use UMFlint\Html\Form\Frameworks\Framework;
use UMFlint\Html\Form\RuleParser;

class Input extends Element
{
    /**
     * @var Framework
     */
    protected $framework;

    /**
     * Default framework class.
     */
    const FRAMEWORK = Bootstrap3::class;

    /**
     * The default element type.
     *
     * @var string
     */
    protected $type = 'input';

    /**
     * The type of input.
     *
     * @var string
     */
    protected $inputType = null;

    /**
     * The label.
     *
     * @var string
     */
    protected $label;

    /**
     * Show the label or not.
     *
     * @var bool
     */
    protected $showLabel = true;


    /**
     * @var RuleParser
     */
    protected $ruleParser;

    /**
     * Validation errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Set the help information for the input.
     *
     * @var null
     */
    protected $help = null;

    /**
     * Input constructor.
     *
     * @param           $name
     * @param null      $value
     * @param Framework $framework
     */
    public function __construct($name, $value = null, $framework = null)
    {
        $this->initAttributes();
        $this->initChildren();
        $this->setup($name, $value, $framework);

        return $this;
    }

    /**
     * Handle setting up the class.
     *
     * @author   Donald Wilcox <dowilcox@umflint.edu>
     * @param           $name
     * @param           $value
     * @param Framework $framework
     * @throws \Exception
     */
    protected function setup($name, $value, $framework = null)
    {
        if (!is_null($this->inputType)) {
            $this->set('type', $this->inputType);
        }

        $this->name($name);

        // Auto set label.
        $this->automaticLabel($name);

        // Set value.
        if (!is_null($value)) {
            $this->value($value);
        }

        if (is_null($framework)) {
            $framework = self::FRAMEWORK;
        }

        $this->framework = new $framework($this);
        if (!$this->framework instanceof Framework) {
            throw new \Exception('Framework must be an instance of ' . Framework::class);
        }

        $this->ruleParser = new RuleParser($this);
    }

    /**
     * Get the input type.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * Set the input name.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        // Check for relationship.
        if (strpos($name, '.') !== false) {
            $split = explode('.', $name);

            $name = $split[0];
            unset($split[0]);

            foreach ($split as $item) {
                $name .= "[{$item}]";
            }
        }

        $this->set('name', $name);
        $this->set('id', $name);

        return $this;
    }

    /**
     * Get the ID of the input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * Automatically create label from input name.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $name
     */
    protected function automaticLabel($name)
    {
        // Clean name
        $label = preg_replace('/\[\]$/', '', $name);

        // Replace underscores and periods
        $label = preg_replace('/\./', ' ', $label);
        $label = preg_replace('/_/', ' ', $label);

        // Uppercase each word.
        $label = ucwords($label);

        $this->label($label);
    }

    /**
     * Set the label.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;

        if ($this->label === false) {
            $this->showLabel(false);
        }

        return $this;
    }

    /**
     * Get the label.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Bool to show label or not.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param null $show
     * @return bool
     */
    public function showLabel($show = null)
    {
        if (is_null($show)) {
            return $this->showLabel;
        }

        if ($show) {
            $this->showLabel = true;
        }else {
            $this->showLabel = false;
        }

        return $this;
    }

    /**
     * Set the value;
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        if ($this->isVoid()) {
            $this->set('value', $value);
        }else {
            $this->emptyChildren()->appendChild($value);
        }

        return $this;
    }

    /**
     * Apply validation rules.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $rules
     * @return $this
     */
    public function rules($rules)
    {
        $attributes = $this->ruleParser->parse($rules);

        foreach ($attributes as $key => $attribute) {
            $this->set($key, $attribute);
        }

        return $this;
    }

    /**
     * Set validation errors.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Get the validation errors.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Bool to check for errors.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->getErrors());
    }

    /**
     * Set help text for input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $help
     * @return $this
     */
    public function help($help)
    {
        $this->help = $help;

        return $this;
    }

    /**
     * Get the help text.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return null
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Bool to check for help.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return bool
     */
    public function hasHelp()
    {
        return !is_null($this->getHelp());
    }

    /**
     * See if input is required.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return bool
     */
    public function isRequired()
    {
        if ($this->get('required') == 'required') {
            return true;
        }

        return false;
    }

    /**
     * Render input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return mixed
     */
    public function __toString()
    {
        return $this->framework->render();
    }
}