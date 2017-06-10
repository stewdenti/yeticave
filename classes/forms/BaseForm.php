<?php

/**
 * Базовый Класс для обработки форм
 *
 */
class BaseForm
{
    protected $errors;
    protected $data;
    protected $rules;

    protected static function fields()
    {
        throw new Exception("Error Processing Request", 1);
    }

    protected static function formName()
    {
        throw new Exception("error");
    }

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;

            foreach ($this->rules as $rule => $fields) {
                if (in_array($key, $fields)) {
                    $this->runValidator($rule, $key);
                }
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function __get($field)
    {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        } else {
            return null;
        }
    }

    public function isValid()
    {
        return empty($this->errors) ? true: false;
    }

    public static function getFormData()
    {
        $formName = static::formName();
        $formFields = static::fields();
        $data = [];

        foreach ($formFields as $field) {
            if (array_key_exists($field, $_REQUEST)){
                $data[$field] = $_REQUEST[$field];
            } else {
                $data[$field] = null;
            }

        }

        return new $formName($data);
    }

    protected function runValidator($name, $field)
    {
        $method = "run".ucfirst($name)."Validator";
        if (method_exists($this, $method)) {
            $this->$method($field);
        }
    }

    protected function runRequiredValidator($field) {

        if (in_array($field,array_keys($this->data)) &&  empty($this->data[$field])) {
            $this->errors[$field] = "Заполните это поле";
            unset($this->data[$field]);
        }

    }
}



