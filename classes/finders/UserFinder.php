<?php

class UserFinder extends BaseFinder
{
    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName()
    {
        return "users";
    }
    /**
     * возвращает имя класса
     *
     * @return string имя класса
     */
    protected static function entityName()
    {
        return "User";
    }

    /*
     *  Поиск пользователя по email
     *
     * @param string $email email пользователя
     *
     * @return array|null возвращает результат поиска пользователя
     */
    public static function findByEmail($email)
    {
        return self::getOneByKey($key = "email", $email);
    }
    
}