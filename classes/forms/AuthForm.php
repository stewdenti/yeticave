<?php
/**
 * Класс для проверки формы авторизации
 */
class AuthForm extends BaseForm
{
    /**
     * привила валидации
     * @var array
     */
    protected $rules = [
        "email" => ["email"],
        "required" => ["email", "password"],
    ];

    /**
     * возвращает поля формы для обработки
     * @return array список полей формы
     */
    protected static function fields()
    {
        return ["email", "password"];
    }

    /**
     * возвращает имя обрабатываемой формы
     * @return string|null имя формы
     */
    public static function formName()
    {
        return "AuthForm";
    }

    /**
     * валидатор правильности заполнения поля для email
     * @param  string $field имя поля
     *
     */
    protected function runEmailValidator($field)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Введенный email не соответствует формату email";
            unset($this->data[$field]);
        }

    }



}
