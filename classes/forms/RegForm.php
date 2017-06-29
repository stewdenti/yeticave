<?php
/**
 * Класс для обработки формы регистрации нового пользователя
 */
class RegForm extends BaseForm
{
    /**
     * Правила валидации
     * @var array
     */
    protected $rules = [
        "email" => ["email"],
        "image" => ["avatar_img"],
        "password" => ["password"],
        "required" => ['email', 'password', 'name', 'contacts']
    ];

    /**
     * возвращает поля формы для обработки
     * @return array список полей формы
     */
    protected static function fields()
    {
        return ['email', 'password', 'name', 'contacts', 'avatar_img'];
    }

    /**
     * возвращает имя обрабатываемой формы
     * @return string|null имя формы
     */
    public static function formName()
    {
        return "RegForm";
    }

    /**
     * валидатор правильности заполнения поля для email
     * @param  string $field имя поля
     *
     */
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
    /**
     * валидатор для правильности заполенения и хеширования пароля
     * @param  string $field имя поля

     */
    protected function runPasswordValidator($field)
    {
        $this->data[$field] = password_hash($this->$field,PASSWORD_BCRYPT);
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
                    $store_image = ConfigManager::getConfig()->img_path;
                    $name = $store_image."/" . time() . "." . $p[1];//делаем имя равным текущему времени в секундах
                    move_uploaded_file($file['tmp_name'], ConfigManager::getConfig()->doc_root.$name);//добавляем файл в папку
                    $this->data[$field] = $name;//путь до файла
                } else {
                    $this->data[$field] = $_FILES[$field]["name"];
                    $this->errors[$field] = "Попытка добавить файл недопустимого формата";
                }
            } else {
                $this->errors[$field] = "файл не может быть загружен";
                $this->data[$field] = $_FILES[$field]["name"];
            }
        } else {
            $this->data[$field] = "/img/user.jpg";
        }
    }
}


?>
