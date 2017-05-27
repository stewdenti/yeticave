<?php
/**
 *  Класс для работы с категориями
 *
 */
class Category extends BaseRecord {

    public $id;
    public $name;

    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName() {
        return 'categories';
    }
    
    /**
     * возвращает список полей таблицы
     * @return array список полей
     */

    public function dbFields()
    {
        return ['id', 'name'];
    }
}

