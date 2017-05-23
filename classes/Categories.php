<?php

class Categories {


    public static function getAll()
    {
        $sql = "SELECT `id`, `name` FROM categories;";
        $categories = DB::dataRetrievalAssoc($sql, []);
        return $categories;
    }

}
