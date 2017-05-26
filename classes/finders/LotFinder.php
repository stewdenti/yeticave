<?php

class LotFinder extends BaseFinder
{
    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName()
    {
        return "lots";
    }

    /**
     * возвращает имя класса
     *
     * @return string имя класса
     */
    protected static function entityName()
    {
        
        return "Lot";
    }

    /**
     * Получение списка лотов для заданной категории
     *
     * @param integer $categoryId id категории по которой нужно найти лоты
     * @return Lot[] массив всех лотов для заданной категории
     */
    public static function getByCategoryId($categoryId)
    {
        $categoryId = protectXSS($categoryId);
        $sql = "SELECT * FROM ".self::tableName()." WHERE end_date > NOW() AND winner is NULL AND category_id = ? ORDER BY add_date DESC LIMIT 9;";
        return array_map(
            function($l) {
                $entity = self::entityName();
                return new $entity($l);
            },
            DB::getInstance()->getAll($sql, [$categoryId])
        );
    }

     /**
     *  получение всех открытых лотов
     *
     * @return Lot[] список всех лотов
     */
    public static function getAllOpened()
    {
        $sql = "SELECT * FROM ".self::tableName()." WHERE end_date > NOW() and winner is NULL ORDER BY add_date DESC LIMIT 9;";
        return array_map(
            function($l) {
                $entity = self::entityName();
                return new $entity($l);
            },
            DB::getInstance()->getAll($sql, [])
        );
    }


    /**
     * Возвращает лоты пользователя
     * @param $userId
     * @return Lot[]
     * @throws Exception
     */
    public static function getByUserId($userId) {
        $sql = "SELECT * FROM ".self::tableName()." WHERE user_id = ? ORDER BY add_date DESC LIMIT 9;";
        return array_map(
            function($l) {
                $entity = self::entityName();
                return new $entity($l);
            },
            DB::getInstance()->getAll($sql, [$userId])
        );
    }
    
}