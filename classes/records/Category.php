<?php
/**
 *  Класс для работы с категориями
 *
 */
class Category extends BaseRecord {
    
    protected $id;
    protected $name;

    protected static function tableName() {
        return 'categories';
    }

    public function dbFields()
    {
        return ['id', 'name'];
    }


    // /**
    //  * @return Category[]
    //  */
    // public static function getAll()
    // {
    //     $sql = "SELECT `id`, `name` FROM categories;";
    //     return array_map(
    //         function($c) {
    //             return new Category($c);
    //         },
    //         DB::getInstance()->getAll($sql, [])
    //     );
    // }

}

