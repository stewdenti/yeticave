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
     * @return User в сессии
     */
    public static function getAuthData ()
    {
        $result = array ();
        if (self::checkAccess()) {
            $result = $_SESSION["user"];
        }
        return $result;
    }

    /**
     * Авторизация пользователя по email и паролю. если авторизация успешна
     * в переменную сессии будут сохранены данные пользователяи возратится результат true
     * и наче вернется массив с ошибками
     *
     * @param string $email email пользователя для авторизации
     * @param string $password пароль пользователя для авторизации
     * @return boolean|array если авторизация успешна возвращает true, если нет массив с ошибками
     */
    public static function authorize($email, $password)
    {
        $errors = array();

        $user = UserFinder::findByEmail($email);
        if ($user) { //поиск пользователя по email
            if (password_verify($password, $user->password)) {//сравнение пароля с хешом пароля в массиве
                session_start();
                $_SESSION["user"] = $user;
                return true;
            } else {
                $errors["wrong_message"] = "Вы ввели не верный email или пароль";
            }
        } else {
            $errors["wrong_message"] = "Вы ввели не верный email или пароль";
        }
        return $errors;
    }

    /**
     * удаление данных о сессии
     */
    public static function logout ()
    {
        session_unset();
        session_destroy();
    }

}
