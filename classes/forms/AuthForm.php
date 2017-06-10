<?php


class AuthForm extends BaseForm
{
    protected $rules = [
        "email" => ["email"],
        "required" => ["email", "password"],
    ];

    protected static function fields()
    {
        return ["email", "password"];
    }

    public static function formName()
    {
        return "AuthForm";
    }


    protected function runEmailValidator($field)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Введенный email не соответствует формату email";
            unset($this->data[$field]);
        }

    }



}
