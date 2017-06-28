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

    /**
     * возвращает поля формы для обработки
     * @return array список полей формы
     */
    protected static function fields()
    {
        throw new Exception("Error Processing Request", 1);
    }

    /**
     * возвращает имя обрабатываемой формы
     * @return string|null имя формы
     */
    protected static function formName()
    {
        throw new Exception("error");
    }

    /**
     * Конструктор объекта формы. Осущствляет валидацию полей формы
     * @param array $data полученные данные через форму
     */
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
    /**
     * получить список данный из полей формы
     * @return array список полученных данных
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Получить ошибки заполнения формы
     *
     * @return array массив ошибок при обработке формы
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Геттер для получения значения по именя поля в форме
     * @param  string $field имя поля в форме
     * @return string|null    значение из поля формы
     */
    public function __get($field)
    {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        } else {
            return null;
        }
    }
    /**
     * правильно ли были заполнены все поля формы
     * @return boolean если не было найдено ошибок то true, иначе false
     */
    public function isValid()
    {
        return empty($this->errors) ? true: false;
    }

    /**
     * получение всех данных из запроса отправленных в форме
     * @return $BaseForm объект формы
     */
    public static function getFormData()
    {
        $formName = static::formName();
        $formFields = static::fields();
        $data = [];

        foreach ($formFields as $field) {
            if (array_key_exists($field, $_REQUEST)){
                $data[$field] = Protection::fromXSS($_REQUEST[$field]);
            } else {
                $data[$field] = null;
            }

        }

        return new $formName($data);
    }

    /**
     * выполнить валидацию поля формы согласно правилам
     * @param  string $name  имя правила валидации
     * @param  string $field имя поля для валидации

     */
    protected function runValidator($name, $field)
    {
        $method = "run".ucfirst($name)."Validator";
        if (method_exists($this, $method)) {
            $this->$method($field);
        }
    }
    /**
     * Выполнить валидацию обязательных полей для формы
     * @param  string $field имя поля для валидации

     */
    protected function runRequiredValidator($field) {

        if (in_array($field,array_keys($this->data)) &&  empty($this->data[$field])) {
            $this->errors[$field] = "Заполните это поле";
            unset($this->data[$field]);
        }

    }
}



