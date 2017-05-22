<?php

class Lots {

    public static function getByCategoryId($category_id)
    {
        $id = protectXSS($category_id);
        $sql = "SELECT lots.id, lots.`name`,`categories`.`name` AS 'category',
        if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price,
        if (COUNT(binds.price) > 0, COUNT(binds.price), 0) as binds_number,
        `img_path`, `end_date`
        FROM lots JOIN `categories` ON lots.`category_id` = `categories`.id
        LEFT JOIN binds ON lots.id = binds.`lot_id`
        WHERE `end_date` > NOW() AND winner is NULL AND category_id = ?
        GROUP BY lots.id ORDER BY lots.add_date DESC
        LIMIT 9;
        ";
        $lots_data = DB::dataRetrievalAssoc($sql, [$id]);
        return $lots_data;
    }

    public static function getAllOpened()
    {
        $sql = "SELECT lots.id, lots.`name`,`categories`.`name` AS 'category',
        if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price,
        if (COUNT(binds.price) > 0, COUNT(binds.price), 0) as binds_number,
        `img_path`, `end_date`
        FROM lots JOIN `categories` ON lots.`category_id` = `categories`.id
        LEFT JOIN binds ON lots.id = binds.`lot_id` WHERE `end_date` > NOW() and winner is NULL
        GROUP BY lots.id ORDER BY lots.add_date DESC
        LIMIT 9;";
        $lots_data = DB::dataRetrievalAssoc($sql, []);
        return $lots_data;
    }

    public static function getByKey($key="id", $value="")
    {
        $sql = "SELECT lots.id, lots.`name`, `img_path`, `categories`.`name` AS 'category',
         if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price,
        description, step, start_price, end_date
        FROM lots
        JOIN `categories`
        ON lots.`category_id` = `categories`.id
        LEFT JOIN binds ON lots.id = binds.`lot_id`
        WHERE lots.$key = ?
        GROUP BY lots.id";

        $lot_data = DB::dataRetrievalAssoc($sql,[$value],true);

        if ($lot_data) {
            return $lot_data;
        } else {
            return false;
        }
    }

    public static function addNew($data=array())
    {
        $sql = "INSERT lots SET user_id = ?, category_id=?, name=?, description=?, img_path=?,
                start_price=?, step=?, end_date=?, add_date=NOW()";

        $lot_id = DB::dataInsertion($sql, [
            $data["user_id"], $data["category"], $data["lot-name"], $data["message"], $data["URL-img"],
            $data["price"], $data["lot-step"], date("Y:m:d H:i",strtotime($data["lot-date"]))
        ]);

        if ($lot_id) {
            return $lot_id;
        } else {
            return false;
        }
    }
}
