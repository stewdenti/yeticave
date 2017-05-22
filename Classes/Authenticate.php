<?php

class Authenticate {

    protected $user = null;
    protected $errors = null;

    public function __construct()
    {
        if (isset($_SESSION["user"])) {
            $this->user = $_SESSION["user"];
        }
    }

    public function blockAccess()
    {

        if (!$this->user) {
             header("HTTP/1.1 403 Forbidden");
             echo "Доступ закрыт для анонимных пользователей";
             exit();
        }



    }

    public function getAuthorizedData ($key = null)
    {
        if ($this->user) {
            if ($key) {
                return $this->user[$key];
            } else {
                return array (
                "user_id" => $this->user["id"],
                "username" =>  $this->user["name"],
                "avatar" =>  $this->user["avatar_img"]);
                }
        } else {
            return array();
        }



    }

    protected function getUserByKey($key="email", $value="")
    {

        $sql = "SELECT * FROM users WHERE $key=?;";
        $result = DB::dataRetrievalAssoc($sql, [$value], true);
        if ($result) {
           $this->user = $result;
        }

    }

    public function authorize($email,$password)

    {
        $this->getUserByKey("email",$email);
        if ($this->user) { //поиск пользователя по email
            if (password_verify($password, $this->user["password"])) {//сравнение пароля с хешом пароля в массиве
                session_start();
                $_SESSION["user"] = $this->user;
                header("Location: /index.php");//если пароль верен, отправляем пользователя на главную стр
                exit();
            } else {
                $this->errors["wrong_password"] = "Вы ввели не верный пароль";
                $this->errors["password"] = True;
            }
        } else {
            $this->errors["wrong_username"] = "Пользователья с такием email не существует";
            $this->errors["email"] = True;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }


}
