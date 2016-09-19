<?php

namespace UMFlint\Html\Form;

use Illuminate\Contracts\Support\Arrayable;
use UMFlint\Html\Element;
use UMFlint\Html\Form\Input\Hidden;

class Form extends Element
{
    /**
     * Form config.
     *
     * @var array
     */
    protected $config = [];

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
        parent::__construct('form');
        $this->config = $config;
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
        $this->set('action', $url)->addClass($this->config[$this->config['framework']]['form']['class']);

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
        $this->appendChild((new Hidden('_method', $this->get('method')))->render());

        if (function_exists('csrf_token')) {
            $this->appendChild((new Hidden('_token', csrf_token()))->render());
        }
        
        return str_replace('</form>', '', $this->render());
    }
}