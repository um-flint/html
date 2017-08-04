<?php

namespace UMFlint\Html\Form\Input;

use UMFlint\Html\Element;
use UMFlint\Html\Form\RuleParser;

class Input extends Element
{
    /**
     * The default element tag.
     *
     * @var string
     */
    protected $tag = 'input';

    /**
     * The type of input.
     *
     * @var string
     */
    protected $type = null;

    /**
     * @var RuleParser
     */
    protected $ruleParser;

    /**
     * Input constructor.
     *
     * @param           $name
     * @param null      $value
     */
    public function __construct($name, $value = null)
    {
        $this->initAttributes();
        $this->initChildren();
        $this->setup($name, $value);

        return $this;
    }

    /**
     * Handle setting up the class.
     *
     * @author   Donald Wilcox <dowilcox@umflint.edu>
     * @param           $name
     * @param           $value
     * @throws \Exception
     */
    protected function setup($name, $value)
    {
        if (!is_null($this->type)) {
            $this->set('type', $this->type);
        }

        $this->name($name);

        // Set value.
        if (!is_null($value)) {
            $this->value($value);
        }

        $this->ruleParser = new RuleParser($this);
    }

    /**
     * Get the input type.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
        $this->id($name);

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
     * Set the placeholder for the input.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $value
     * @return $this
     */
    public function placeholder($value)
    {
        $this->set('placeholder', $value);

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
}