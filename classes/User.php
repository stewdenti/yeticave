<?php
/**
 * Класс для работы с пользователями
 *
 */
class User {

    /**
     * @param array $data массив с данными для добавления в таблицу
     *
     * @return booleans результат выполнения команды
     */
    public static function addNew($data = array())
    {
        $sql = "INSERT users SET email = ?, password = ?, name = ?,
                contacts = ?, avatar_img = ?";
        $unitDataSql = [];
        foreach ($data as $value) {
            $unitDataSql[] = $value;
        }
        $user_id = DB::getInstance()->dataInsertion($sql,  $unitDataSql);
        if ($user_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Поиск пользователя по email
     *
     * @param string $email email пользователя
     *
     * @return array|null возвращает результат поиска пользователя
     */
    public static function findByMail($email)
    {
        return self::getUserByKey($key = "email", $value = $email);
    }

    /**
     * @param  string $key ключ для поиска пользователя в таблице
     * @param  string $value значения ключа для поиска пользователя
     *
     * @return array|null
     */
    protected static function getUserByKey($key="email", $value="")
    {
        $result = null;
        $sql = "SELECT * FROM users WHERE $key=?;";
        $result = DB::getInstance()->getOne($sql, [$value]);
        return $result;
    }


}

