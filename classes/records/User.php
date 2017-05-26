<?php

/**
 * Класс для работы с пользователями
 */
class User extends BaseRecord {

    protected $email;
    protected $password;
    protected $name;
    protected $contacts;
    protected $avatar_img;

    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName() {
        return 'users';
    }

    /**
     * возвращает список полей таблицы
     * @return array список полей
     */

    public function dbFields()
    {
        return ['id', 'email', 'password', 'name', 'contacts', 'avatar_img'];
    }

    // /**
    //  * @param array $data массив с данными для добавления в таблицу
    //  *
    //  * @return boolean результат выполнения команды
    //  */
    // public static function addNew($data = array())
    // {
    //     $sql = "INSERT users SET email = ?, password = ?, name = ?,
    //             contacts = ?, avatar_img = ?";
    //     $unitDataSql = [];
    //     foreach ($data as $value) {
    //         $unitDataSql[] = $value;
    //     }
    //     $user_id = DB::getInstance()->dataInsertion($sql,  $unitDataSql);
    //     return $user_id ? true : false;
    // }

}

