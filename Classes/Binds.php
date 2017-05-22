<?php

class Binds {

    public static function getAllLotsByUser ($user_id)
    {
        $sql = "SELECT lots.id, lots.name, lots.img_path, categories.name AS category, binds.price, binds.date, lots.end_date
        FROM lots
        JOIN binds on binds.lot_id=lots.id
        JOIN categories on lots.category_id=categories.id
        WHERE binds.user_id=? AND `end_date` > NOW()";
        $result = DB::dataRetrievalAssoc($sql, [$user_id]);
        return $result;
    }

    public static function  isPutAllowed($lot_id, $user_id)
    {
        $sql = "SELECT COUNT(price) AS number FROM binds
        WHERE lot_id=? AND user_id=? ";

        $res = DB::dataRetrievalAssoc($sql, [ $lot_id, $user_id], true);
        if ($res["number"] > 0 ) {
            return false;
        } else {
            return true;
        }
    }

    public static function getByLotID($id)
    {
        $sql = "SELECT users.name AS name, binds.price, binds.date FROM binds
        JOIN users ON binds.user_id=users.id
        JOIN lots ON lots.id = binds.lot_id
        WHERE binds.lot_id=? AND binds.price != lots.start_price
        ORDER BY price DESC";
        $bets = DB::dataRetrievalAssoc($sql, [$id]);
        return $bets;
    }

    public static function addNew($data = array())
    {
        $sql = "INSERT binds SET user_id=?, lot_id=?, price=?, date=NOW();";

        $result = DB::dataInsertion($sql, [$data["user_id"], $data["lot_id"], $data["cost"]]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMax($lot_id)
    {
        $sql = "SELECT if(MAX( binds.`price` ), MAX( binds.`price`), start_price) AS max_price, step
        FROM lots LEFT JOIN binds ON lots.id=binds.lot_id WHERE lots.id=? GROUP BY lots.id ;";
        $result = DB::dataRetrievalAssoc($sql, [$lot_id], true);
        return $result["max_price"] + $result["step"];
    }

}
