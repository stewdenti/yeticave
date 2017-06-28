<?php
/**
 * Класс для валидации формы создания нового лота
 */
class AddForm extends BaseForm
{
    /**
     * привила валидации
     * @var array
     */
    protected $rules = [
        "int" => ["start_price", "step"],
        "image" => ["img_path"],
        "date" => ["end_date"],
        "required" => ['category_id', 'name', 'description', 'img_path',
            'start_price', 'step', 'end_date']
    ];

    /**
     * возвращает поля формы для обработки
     * @return array список полей формы
     */
    protected static function fields()
    {
        return [
            'category_id', 'name', 'description', 'img_path',
            'start_price', 'step', 'end_date'
        ];
    }

    /**
     * возвращает имя обрабатываемой формы
     * @return string имя формы
     */
    public static function formName()
    {
        return "AddForm";
    }

    /**
     * Валидатор правильности заполенния полей целочисленными значениями
     * @param  string $field имя поля
     */
    protected function runIntValidator($field)
    {
        if (!is_numeric($this->$field)) {
            $this->errors[$field] = "Здесь может быть только число";
        }
    }

    /**
     * Валидатор правильности заполнения даты
     * @param  string $field имя поля
     */
    protected function runDateValidator($field)
    {
        if (strtotime($this->$field) < time()) {
            $this->errors["end_date"] = "Время оконачания лота задано в прошлом";
        }

    }

    /**
     * валидатор загружаемой картинки
     * @param  string $field имя поля где передается файл

     */
    protected function runImageValidator($field)
    {

        if (!empty($_FILES[$field]["name"])) {
            $file = $_FILES[$field];
            //Проверяем принят ли файл
            if (file_exists($file['tmp_name'])) {
                $info = @getimagesize($file['tmp_name']);
                if (preg_match('{image/(.*)}is', $info["mime"], $p)) {
                    $store_image = ConfigManager::getSettings("img_path");
                    $name = $store_image."/" . time() . "." . $p[1];//делаем имя равным текущему времени в секундах
                    move_uploaded_file($file['tmp_name'], $name);//добавляем файл в папку
                    $this->data[$field] = "/".$name;//путь до папки
                } else {
                    $this->data[$field] = $_FILES[$field]["name"];
                    $this->errors[$field] = "Попытка добавить файл недопустимого формата";
                }
            } else {
                $this->errors[$field] = "файл не может быть загружен";
                $this->data[$field] = $_FILES[$field]["name"];
            }
        } else {
            $this->errors[$field] = "Файл не был отправлен";
        }
    }
}
