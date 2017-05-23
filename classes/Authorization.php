<?php
/**
 * Класс для проверки авторизации и хранения авторизации
 */
class Authorization {

    /**
     * Проверка прошел ли пользователь процедура авторизации
     *
     * @return booleans авторизован пользователь или ент
     */
    public static function checkAccess()
    {
        @session_start();
        if (!empty($_SESSION["user"])) {
            return true;
        }
        return false;
    }

    /**
     * блокировать доступ к странице если пользователь не прошел авторизацию
     */
    public static function blockAccess()
    {
        if (!self::checkAccess()) {
            header("HTTP/1.1 403 Forbidden");
            echo "Доступ закрыт для анонимных пользователей";
            exit();
        }
    }

    /**
     * получить данные о пользователе, которые хранятся в переменной сессии
     * если пользователь не авторизован,то вернется пустой массив
     *
     * @return array массив данных о пользователе хранящихся в сессии
     */
    public static function getAuthData ()
    {
        if (self::checkAccess()) {
            return array (
                "user_id" => $_SESSION["user"]["id"],
                "username" => $_SESSION["user"]["name"],
                "avatar" => $_SESSION["user"]["avatar_img"]);
        } else {
            return array();
        }
    }

    /**
     * Авторизация пользователя по email и паролю. если авторизация успешна
     * в переменную сессии будут сохранены данные пользователяи возратится результат true
     * и наче вернется массив с ошибками
     *
     * @param string $email email пользователя для авторизации
     * @param string $password пароль пользователя для авторизации
     * @return [type]
     */
    public static function authorize($email,$password)
    {
        $errors = array();

        $user = User::findByMail($email);
        if ($user) { //поиск пользователя по email
            if (password_verify($password, $user["password"])) {//сравнение пароля с хешом пароля в массиве
                session_start();
                $_SESSION["user"] = $user;
                return true;
            } else {
                $errors["wrong_password"] = "Вы ввели не верный пароль";
                $errors["password"] = True;
            }
        } else {
            $errors["wrong_username"] = "Пользователья с такием email не существует";
            $errors["email"] = True;
        }
        return $errors;
    }

    /**
     * удаление данных о сессии
     */
    public static function logout ()
    {
        session_start();
        session_unset();
        session_destroy();
    }

}
