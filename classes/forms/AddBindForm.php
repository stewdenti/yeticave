<?php
/**
 * Класс для проверки форма выставления ставки
 */
class AddBindForm extends BaseForm
{
    /**
     * привила валидации
     * @var array
     */
    protected $rules = [
        "int" => ["price"],
        "required" => ["price","lot_id"],
    ];

    /**
     * возвращает поля формы для обработки
     * @return array список полей формы
     */
    protected static function fields()
    {
        return [
            'lot_id', 'price'
        ];
    }

    /**
     * возвращает имя обрабатываемой формы
     * @return string имя формы
     */
    public static function formName()
    {
        return "AddBindForm";
    }

    /**
     * Валидатор правильности заполенния полей целочисленными значениями
     * @param  string $field имя поля
     */
    protected function runIntValidator($field)
    {
        if (!is_numeric($this->$field) || $this->$field < 0) {
            $this->errors[$field] = "Введите цену больше или равной минимальной ставки";
        }
    }


}
