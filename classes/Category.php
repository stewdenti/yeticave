<?php
/**
 *  Класс для работы с категориями
 *
 */
class Category {

    /**
     * @return array массив ассоциативных массив всех категорий
     */
    public static function getAll()
    {
        $sql = "SELECT `id`, `name` FROM categories;";
        $categories =  DB::getInstance()->getAll($sql, []);
        return $categories;
    }

}
