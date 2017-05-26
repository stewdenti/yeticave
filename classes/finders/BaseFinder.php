<?php

abstract class BaseFinder 
{
    
    

    protected static function tableName()
    {
        throw new Exception('not implemented');
    }

    protected static function entityName()
    {
        throw new Exception('not implemented');
    }

    /**
     * Выбор записи по id с созданием объекта класса, у которого этот метод вызван
     * Например User::getById() создаст объект пользователя
     * @param $id
     * @return BaseRecord
     * @throws Exception
     */
    public static function getById($id)
    {
        $sql = "SELECT * FROM ".static::tableName()." WHERE id = ?";
        $result = DB::getInstance()->getOne($sql, [$id]);
        $entityName =  static::entityName();
        return new $entityName($result);
        
    }

    /**
     * Поиск в таблице по ключу, возвращает объект класса, у которого метод вызван
     * @param  string $key ключ для поиска в таблице
     * @param  string $value значения ключа для поиска
     *
     * @return array|null
     */
    protected static function getByKey($key, $value)
    {
        $sql = "SELECT * FROM ".static::tableName()." WHERE $key=?;";
        $result = DB::getInstance()->getOne($sql, [$value]);
        $entityName =  static::entityName();
        return new $entityName($result);
    }


    public static function getAll()
    {
        $sql = "SELECT * FROM ".static::tableName().";";
        return array_map(
            function($c) {
                $entityName =  static::entityName();
                return new $entityName($c);
            },
            DB::getInstance()->getAll($sql, [])
        );
    }
}

