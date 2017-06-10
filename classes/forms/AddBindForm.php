<?php

class AddBindForm extends BaseForm
{
    protected $rules = [
        "int" => ["price"],
        "required" => ["price","lot_id"],
    ];

    protected static function fields()
    {
        return [
            'lot_id', 'price'
        ];
    }

    public static function formName()
    {
        return "AddBindForm";
    }

    protected function runIntValidator($field)
    {
        if (!is_numeric($this->$field) || $this->$field < 0) {
            $this->errors[$field] = "Введите цену больше или равной минимальной ставки";
        }
    }


}
