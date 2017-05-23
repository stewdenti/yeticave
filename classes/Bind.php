<?php
/**
 * Класс для работы со ставками
 */
class Bind {
    /**
     * Разрешено ли пользовальтелю сделать ставку для лота
     *
     * @param  integer $lot_id id лота
     * @param  integer $user_id id пользователя
     *
     * @return boolean разрешено или нет
     */
    public static function  isPutAllowed($lot_id, $user_id)
    {
        $sql = "SELECT COUNT(price) AS number FROM binds
        WHERE lot_id=? AND user_id=? ";

        $res = DB::getOne($sql, [ $lot_id, $user_id]);
        return $res["number"]<=0;
    }

    /**
     * получение всех ставок для лота
     * @param  integer $id id лота
     *
     * @return array списко всех ставок
     */
    public static function getByLotID($id)
    {
        $sql = "SELECT users.name AS name, binds.price, binds.date FROM binds
        JOIN users ON binds.user_id=users.id
        JOIN lots ON lots.id = binds.lot_id
        WHERE binds.lot_id=? AND binds.price != lots.start_price
        ORDER BY price DESC";
        $bets = DB::getAll($sql, [$id]);
        return $bets;
    }

    /**
     * Добавление в таблицу новой ставки
     *
     * @param array $data массив с данными для добавления новой ставки
     *
     * @return booleans успешна ли добавлена новая запись или нет
     */
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

    /**
     * Получение максимальной ставик для лота
     * @param integer $lot_id id лота для которого нужно найти максимальную ставку
     *
     * @return integer минимальная ставка которую должен сделать пользователь
     */
    public static function getMax($lot_id)
    {
        $sql = "SELECT if(MAX( binds.`price` ), MAX( binds.`price`), start_price) AS max_price, step
        FROM lots LEFT JOIN binds ON lots.id=binds.lot_id WHERE lots.id=? GROUP BY lots.id ;";
        $result = DB::getOne($sql, [$lot_id]);
        return $result["max_price"] + $result["step"];
    }

}
