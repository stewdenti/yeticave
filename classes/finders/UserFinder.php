<?php

class UserFinder extends BaseFinder
{

    protected static function tableName()
    {
        return "users";
    }

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
        return self::getByKey($key = "email", $email);
    }
    
}