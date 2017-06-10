<?php

class RegForm extends BaseForm
{
    protected $rules = [
        "email" => ["email"],
        "image" => ["avatar_img"],
        "password" => ["password"],
        "required" => ['email', 'password', 'name', 'contacts']
    ];

    protected static function fields()
    {
        return ['email', 'password', 'name', 'contacts', 'avatar_img'];
    }

    public static function formName()
    {
        return "RegForm";
    }

    protected function runEmailValidator($field)
    {
        if (!filter_var($this->$field, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Введенный email не соответствует формату email";
            // unset($this->data[$field]);
            return;
        }

        if (UserFinder::findByEmail($this->$field)) {
            $this->errors[$field] = "Пользователь с таким email уже существует";
        }
    }
    protected function runPasswordValidator($field)
    {
        $this->data[$field] = password_hash($this->$field,PASSWORD_BCRYPT);
    }

    protected function runImageValidator($field)
    {
        if (!empty($_FILES[$field]["name"])) {
            $file = $_FILES[$field];
            //Проверяем принят ли файл
            if (file_exists($file['tmp_name'])) {
                $info = @getimagesize($file['tmp_name']);
                if (preg_match('{image/(.*)}is', $info["mime"], $p)) {
                    $name = "img/" . time() . "." . $p[1];//делаем имя равным текущему времени в секундах
                    move_uploaded_file($file['tmp_name'], $name);//добавляем файл в папку
                    $this->data[$field] = $name;//путь до папки
                } else {
                    $this->errors[$field] = "Попытка добавить файл недопустимого формата";
                }
            } else {
                $this->errors[$field] = "файл не может быть загружен";
            }
        } else {
            $this->data[$field] = "/img/user.jpg";
        }
    }
}


?>
