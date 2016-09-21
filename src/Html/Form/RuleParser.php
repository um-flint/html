<?php

namespace UMFlint\Html\Form;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use UMFlint\Html\Form\Input\Input;

/**
 * Class RuleParser
 * Based on the RulesParser from kristijanhusak/laravel-form-builder.
 *
 * @see https://github.com/kristijanhusak/laravel-form-builder
 */
class RuleParser
{
    /**
     * @var Input
     */
    protected $input;

    /**
     * RuleParser constructor.
     *
     * @param Input $input
     */
    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    /**
     * Parse a rule for an input into an array of attributes.
     *
     * @param  string|array $rules
     * @return array
     */
    public function parse($rules)
    {
        $attributes = [];

        $rules = $rule = (is_string($rules)) ? explode('|', $rules) : $rules;
        foreach ($rules as $rule) {
            list($rule, $parameters) = $this->parseRule($rule);

            if ($rule && method_exists($this, $rule)) {
                $attributes += $this->$rule($parameters);
            }
        }

        return $attributes;
    }

    /**
     * Check that a checkbox is accepted. Needs yes, on, 1, or true as value.
     *
     *   accepted  -> required="required"
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-accepted
     */
    protected function accepted()
    {
        return [
            'required' => 'required',
        ];
    }

    /**
     * Check that the field is required.
     *
     *   required  -->  required="required"
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-required
     */
    protected function required()
    {
        return [
            'required' => 'required',
        ];
    }

    /**
     * Check that the input only contains alpha.
     *
     *   alpha  --> pattern="[a-zA-Z]+"
     *
     * @return array
     */
    protected function alpha()
    {
        return [
            'pattern' => '[a-zA-Z]+',
        ];
    }

    /**
     * Check if the input contains only alpha and num.
     *
     *   alpha_num  --> pattern="[a-zA-Z0-9]+"
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-alpha-num
     */
    protected function alphaNum()
    {
        return [
            'pattern' => '[a-zA-Z0-9]+',
        ];
    }

    /**
     * Check if the input contains only alpha, num and dash.
     *
     *   alpha_dash  --> pattern="[a-zA-Z0-9_\-]+"
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-alpha-dash
     */
    protected function alphaDash()
    {
        return [
            'pattern' => '[a-zA-Z0-9_\-]+',
        ];
    }

    /**
     * Check if the field is an integer value. Cannot contain decimals.
     *
     *   integer  --> step="1" (number)
     *   integer  --> pattern="\d+" (text)
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-integer
     */
    protected function integer()
    {
        if ($this->isNumeric()) {
            return ['step' => 1];
        }
        return [
            'pattern' => '\d+',
        ];
    }

    /**
     * Check that a field is numeric. It may contain decimals.
     *
     *   numeric  --> step="any" (number)
     *   numeric  --> pattern="[-+]?[0-9]*[.,]?[0-9]+" (text)
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-numeric
     */
    protected function numeric()
    {
        if ($this->isNumeric()) {
            return [
                'step' => 'any',
            ];
        }

        return [
            'pattern' => '[-+]?[0-9]*[.,]?[0-9]+',
        ];
    }

    /**
     * Check that a value is either 0 or 1, so it can be parsed as bool.
     *
     *   boolean  --> pattern="0|1"
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-boolean
     */
    protected function boolean()
    {
        return [
            'pattern' => '0|1',
        ];
    }

    /**
     * Check that the value is numeric and contains exactly the given digits.
     *
     *   digits:3  --> min="100" max="999"
     *   digits:3  --> pattern="\d{3,5}"  (text)
     *
     * @param $param
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-digits
     */
    protected function digits($param)
    {
        $digits = $param[0];

        if ($this->isNumeric()) {
            return [
                'min' => pow(10, $digits - 1),
                'max' => pow(10, $digits) - 1,
            ];
        }

        return [
            'pattern' => '\d{' . $digits . '}',
        ];
    }

    /**
     * Check that the value is numeric and contains between min/max digits.
     *
     *   digits_between:3,5  --> min="100" max="99999"
     *   digits_between:3,5  --> pattern="\d{3,5}"  (text)
     *
     * @param $param
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-digits-between
     */
    protected function digitsBetween($param)
    {
        list($min, $max) = $param;

        if ($this->isNumeric()) {
            return [
                'min' => pow(10, $min - 1),
                'max' => pow(10, $max) - 1,
            ];
        }

        return [
            'pattern' => '\d{' . $min . ',' . $max . '}',
        ];
    }

    /**
     * For numbers, set the minimum value.
     * For strings, set the minimum number of characters.
     *
     *   min:5  --> min="5"       (number)
     *   min:5  --> minlength="5" (text)
     *
     * @param $param
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-min
     */
    protected function min($param)
    {
        $min = $param[0];

        if ($this->isNumeric()) {
            return [
                'min' => $min,
            ];
        }

        return [
            'minlength' => $min,
        ];
    }

    /**
     * For numbers, set the max value.
     * For strings, set the max number of characters.
     *
     *   max:5  --> max="5"       (number)
     *   max:5  --> maxlength="5" (text)
     *
     * @param $param
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-max
     */
    protected function max($param)
    {
        $max = $param[0];

        if ($this->isNumeric()) {
            return [
                'max' => $max,
            ];
        }

        return [
            'maxlength' => $max,
        ];
    }

    /**
     * For number/range inputs, check if the number is between the values.
     * For strings, check the length of the string.
     *
     *   between:3,5  --> min="3" max="5"             (number)
     *   between:3,5  --> minlength="3" maxlength="5" (text)
     *
     * @param $param
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-between
     */
    protected function between($param)
    {
        list ($min, $max) = $param;
        if ($this->isNumeric()) {
            return [
                'min' => $min,
                'max' => $max,
            ];
        }
        return [
            'minlength' => $min,
            'maxlength' => $max,
        ];
    }

    /**
     * For numbers: Check an exact value
     * For strings: Check the length of the string
     *
     *   size:5 --> min="5" max="5" (number)
     *   size:5 --> pattern=".{5}"  (text)
     *
     * @param $param
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-size
     */
    protected function size($param)
    {
        $size = $param[0];

        if ($this->isNumeric()) {
            return [
                'min' => $size,
                'max' => $size,
            ];
        }

        return [
            'pattern' => '.{' . $size . '}',
        ];
    }

    /**
     * Check if the value is one of the give 'in' rule values
     * by creating a matching pattern.
     *
     *   in:foo,bar  --> pattern="foo|bar"
     *
     * @param $params
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-in
     */
    protected function in($params)
    {
        return [
            'pattern' => implode('|', $params),
        ];
    }

    /**
     * Check if the value is not one of the 'not_in' rule values
     * by creating a pattern value.
     *
     *   not_in:foo,bar  --> pattern="(?:(?!^foo$|^bar$).)*"
     *
     * @param $params
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-not-in
     */
    protected function notIn($params)
    {
        return [
            'pattern' => '(?:(?!^' . join('$|^', $params) . '$).)*',
        ];
    }

    /**
     * Set the 'min' attribute on a date/datetime/datetime-local field,
     * based on the 'before' validation.
     *
     *   after:01-12-2015 -> min="2015-12-01"
     *
     * @param  $params
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-after
     */
    protected function after($params)
    {
        if ($date = $this->getDateAttribute($params[0])) {
            return [
                'min' => $date,
            ];
        }

        return [];
    }

    /**
     * Set the 'min' attribute on a date/datetime/datetime-local field,
     * based on the 'before' validation.
     *
     *   before:01-12-2015 -> max="2015-12-01"
     *
     * @param  $params
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-before
     */
    protected function before($params)
    {
        if ($date = $this->getDateAttribute($params[0])) {
            return [
                'max' => $date,
            ];
        }

        return [];
    }

    /**
     * Add the image mime-type to a file input.
     *
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-image
     * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-accept
     */
    protected function image()
    {
        return [
            'accept' => 'image/*',
        ];
    }

    /**
     * Add the mime types to the accept attribute.
     *
     *  mimes:xls,xlsx  --> accept=".xls, .xlsx"
     *
     * @param  array
     * @return array
     *
     * @see http://laravel.com/docs/5.1/validation#rule-mimes
     * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-accept
     */
    protected function mimes($param)
    {
        $mimes = '.' . implode(', .', $param);

        return [
            'accept' => $mimes,
        ];
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

    protected function isNumeric()
    {
        return $this->isType(['number', 'range']);
    }

    /**
     * Format a date to the correct format, based on the current field.
     *
     * @param $dateStr
     * @return bool|string
     */
    protected function getDateAttribute($dateStr)
    {
        $format = "Y-m-d";

        if ($this->isType(['datetime', 'datetime-local'])) {
            $format .= '\TH:i:s';
        }

        return date($format, strtotime($dateStr));
    }

    /**
     * Extract the rule name and parameters from a rule.
     *
     * @see https://github.com/laravel/framework/blob/5.1/src/Illuminate/Validation/Validator.php
     * @param  array|string $rules
     * @return array
     */
    protected function parseRule($rules)
    {
        if (is_array($rules)) {
            return $this->parseArrayRule($rules);
        }
        return $this->parseStringRule($rules);
    }

    /**
     * Parse an array based rule.
     *
     * @see https://github.com/laravel/framework/blob/5.1/src/Illuminate/Validation/Validator.php
     * @param  array $rules
     * @return array
     */
    protected function parseArrayRule(array $rules)
    {
        return [Str::studly(trim(Arr::get($rules, 0))), array_slice($rules, 1)];
    }

    /**
     * Parse a string based rule.
     *
     * @see https://github.com/laravel/framework/blob/5.1/src/Illuminate/Validation/Validator.php
     * @param  string $rules
     * @return array
     */
    protected function parseStringRule($rules)
    {
        $parameters = [];
        // The format for specifying validation rules and parameters follows an
        // easy {rule}:{parameters} formatting convention. For instance the
        // rule "Max:3" states that the value may only be three letters.
        if (strpos($rules, ':') !== false) {
            list($rules, $parameter) = explode(':', $rules, 2);
            $parameters = $this->parseParameters($rules, $parameter);
        }
        return [Str::studly(trim($rules)), $parameters];
    }

    /**
     * Parse a parameter list.
     *
     * @see https://github.com/laravel/framework/blob/5.1/src/Illuminate/Validation/Validator.php
     * @param  string $rule
     * @param  string $parameter
     * @return array
     */
    protected function parseParameters($rule, $parameter)
    {
        if (strtolower($rule) == 'regex') {
            return [$parameter];
        }
        return str_getcsv($parameter);
    }
}