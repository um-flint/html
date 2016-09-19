<?php

namespace UMFlint\Html\Form;

use Illuminate\Contracts\Support\Arrayable;
use UMFlint\Html\Form\Input\Hidden;
use UMFlint\Html\Traits\HasAttributes;

class Form
{
    use HasAttributes;

    /**
     * Form config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Input values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Input rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Input validation errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Namespace for input classes.
     */
    const INPUT_NAMESPACE = '\UMFlint\Html\Form\Input\\';

    /**
     * Form constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initAttributes();
    }

    /**
     * Open a new form.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $url
     * @param $method
     * @return $this
     */
    public function open($url, $method)
    {
        $this->set('action', $url)->set('method', $method)->set('class', $this->config[$this->config['framework']]['form']['class']);

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
            $input = new $class($name, $value, $this->config['framework']);

            // Set rules for input if we have any.
            $rules = array_get($this->rules, $name, null);
            if (!is_null($rules)) {
                $input->rules($rules);
            }

            // Set errors for input if we have any.
            if (isset($this->errors[$name])) {
                $input->setErrors($this->errors[$name]);
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
        $form = "<form";

        // Add each attribute to the lement.
        foreach ($this->getAttributes()->toArray() as $attriubte => $value) {
            if ($attriubte == 'method') {
                if (strtolower($value) !== 'get') {
                    $value = 'POST';
                }
            }
            $form .= " {$attriubte}=\"{$value}\"";
        }

        $form .= ">";

        $method = (new Hidden('_method', $this->get('method')))->render();

        $form .= $method;

        if (function_exists('csrf_token')) {
            $token = (new Hidden('_token', csrf_token()))->render();
            $form .= $token;
        }

        return $form;
    }
}