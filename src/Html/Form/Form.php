<?php

namespace UMFlint\Html\Form;

use Illuminate\Contracts\Support\Arrayable;
use UMFlint\Html\Element;
use UMFlint\Html\Form\Input\Hidden;

class Form extends Element
{
    /**
     * Default method.
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * Input values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Old input values.
     *
     * @var array
     */
    protected $old = [];

    /**
     * Input rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Namespace for input classes.
     */
    const INPUT_NAMESPACE = '\UMFlint\Html\Form\Input\\';

    /**
     * Form constructor.
     */
    public function __construct()
    {
        parent::__construct('form');
        $this->initAttributes();
    }

    /**
     * Set the method of the form.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $method
     * @return $this
     * @throws \Exception
     */
    public function method($method)
    {
        $method = strtoupper($method);
        $allowedMethods = [
            'GET',
            'HEAD',
            'POST',
            'PUT',
            'DELETE',
            'TRACE',
            'OPTIONS',
            'CONNECT',
            'PATCH',
        ];

        if (!in_array($method, $allowedMethods)) {
            throw new \Exception('Invalid method');
        }

        $this->method = $method;
        if ($this->method !== 'GET') {
            $this->set('method', 'POST');
        }else {
            $this->set('method', $this->method);
        }

        return $this;
    }

    /**
     * Open a new form.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $url
     * @param $method
     * @return $this
     */
    public function open($url, $method = null)
    {
        $this->set('action', $url);

        if (!is_null($method)) {
            $this->method($method);
        }

        return $this;
    }

    /**
     * Helper for accepting file inputs.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return $this
     */
    public function hasFiles()
    {
        $this->set('enctype', 'multipart/form-data');

        return $this;
    }

    /**
     * Set old input values.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array $values
     */
    public function old(array $values)
    {
        $this->old = $values;
    }

    /**
     * Set the default data for inputs.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $data
     * @return $this
     */
    public function populate($data)
    {
        if ($data instanceof Arrayable) {
            $this->values = $data->toArray();
        }else {
            $this->values = (array)$data;
        }

        if (!empty($this->old)) {
            $this->values = $this->old;
        }

        return $this;
    }

    /**
     * Set the validation rules.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param array $rules
     * @return $this
     */
    public function rules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Wrapper for button.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param        $text
     * @param string $type
     * @return Button
     */
    public function button($text, $type = 'submit')
    {
        return new Button($text, $type);
    }

    /**
     * Wrapper for label.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $text
     * @return Label
     */
    public function label($text)
    {
        $label = new Label($text);

        $label->addClass(config('html.label.class'));

        return $label;
    }

    /**
     * Render the end of the form.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function close()
    {
        return '</form>';
    }

    /**
     * Handle calls for input types.
     *
     * @author   Donald Wilcox <dowilcox@umflint.edu>
     * @param $function
     * @param $arguments
     * @return null|Text
     */
    public function __call($function, $arguments)
    {
        $class = self::INPUT_NAMESPACE . ucfirst($function);
        if (class_exists($class)) {
            $name = $arguments[0];
            $input = null;
            $value = null;

            // Set value if we have one from populate function.
            $value = array_get($this->values, $name, null);

            // Create input class.
            $input = new $class($name, $value);

            if ($input->getType() != 'checkbox' && $input->getType() != 'radio') {
                $input->addClass(config('html.input.class'));
            }

            // Set rules for input if we have any.
            $rules = array_get($this->rules, $name, null);
            if (!is_null($rules)) {
                $input->rules($rules);
            }

            return $input;
        }
    }

    /**
     * Render the opening tag of the form.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return string
     */
    public function __toString()
    {
        $this->appendChild((new Hidden('_method', $this->method))->render());

        if (function_exists('csrf_token')) {
            $this->appendChild((new Hidden('_token', csrf_token()))->render());
        }

        return str_replace('</form>', '', $this->render());
    }
}