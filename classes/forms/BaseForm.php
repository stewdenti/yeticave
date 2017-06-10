<?php

/**
 * Базовый Класс для обработки форм 
 *
 */
class BaseForm 
{
    protected $fields;
    protected $rules;
    protected $errors;
    protected $data;

    public $formName;
 
    protected function __construct($data = [])
    {

    }

    public function validate() 
    {
        if (!$this->isSent()) {
            return null;
        }


        foreach ($this->fields as $field) {
            if (empty($_REQUEST[$field])) {
                $this->errors[$field] = "Заполните это поле";
            } else {
                $this->runValidatorField($field, $_REQUEST[$field]);
            }
        }

        return count($this->errors) == 0 ? true : false;
    }

    public static function isSent() 
    {
        return isset($_REQUEST[$this->getFormName]) ? true : false;
    }

    public function getData()
    {
        return $this->fields;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function __get($field)
    {
        if (strpos("error",$field) === false) {
            return $this->errors[$field];
        } else {
            return $this->data[$field];
        }
    }

    protected function runValidatorField($field, $value) 
    {
        $method = "run".ucfirst($field)."Validator";
        if (method_exists($this, $method)) {
            $result = $this->$method($value);
        }


    }
    
}



