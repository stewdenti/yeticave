<?php
/**
 * Класс для работы со ставками
 */
class Bind extends BaseRecord {

    public $user_id;
    public $lot_id;
    public $price;
    public $date;

    protected static function tableName() {
        return 'binds';
    }

    public function dbFields()
    {
        return ['id', 'user_id', 'lot_id', 'price', 'date'];
    }

    /**
     * Возвращает лот, для которого сделана ставка
     * @return Lot
     */
    public function getLot() {
        /** @var Lot $result */
        $result = LotFinder::getById($this->lot_id);
        return $result;
    }

    /**
     * Возвращает имя пользователя, сделавшего ставку
     * @return string
     */
    public function userName() {
        /** @var User $user */
        $user = UserFinder::getById($this->user_id);
        return $user->name;
    }
    
    /**
     * Может ли пользователь сделать ставку
     *
     * @param  integer $lot_id id лота
     * @param  integer $user_id id пользователя
     * @return boolean разрешено или нет
     */
    public static function canMakeBet($lot_id, $user_id)
    {
        $bets = BindFinder::getByLotID($lot_id);  // первая в массиве = последняя по времени
        if (empty($bets)) {
            return true;
        }
        return $bets[0]->user_id != $user_id;
    }

    /**
     * получение всех ставок для лота
     * @param int $lotId
     * @return Bind[]
     */
    // public static function getByLotID($lotId)
    // {
    //     $sql = "SELECT binds.* FROM binds WHERE binds.lot_id=? ORDER BY price DESC";
    //     return array_map(
    //         function($b) {
    //             return new Bind($b);
    //         },
    //         DB::getInstance()->getAll($sql, [protectXSS($lotId)])
    //     );
    // }

    /**
     * получение всех ставок для лота
     * @param int $userId
     * @return Bind[]
     */
    // public static function getByUserId($userId)
    // {
    //     $sql = "SELECT * FROM binds WHERE user_id=? ORDER BY price DESC";
    //     return array_map(
    //         function($b) {
    //             return new Bind($b);
    //         },
    //         DB::getInstance()->getAll($sql, [protectXSS($userId)])
    //     );
    // }

    /**
     * Добавление в таблицу новой ставки
     *
     * @param array $data массив с данными для добавления новой ставки
     *
     * @return boolean успешна ли добавлена новая запись или нет
     */
    // public static function addNew($data = array())
    // {
    //     $sql = "INSERT binds SET user_id=?, lot_id=?, price=?, date=NOW();";
    //     $result = DB::getInstance()->dataInsertion($sql, [$data["user_id"], $data["lot_id"], $data["cost"]]);
    //     return $result ? true : false;
    // }

    /**
     * Получение максимальной ставик для лота
     * @param integer $lot_id id лота для которого нужно найти максимальную ставку
     *
     * @return integer минимальная ставка которую должен сделать пользователь
     */
    // public static function getMax($lot_id)  // TODO: минимальная ставка, getMax, WTF?
    // {
    //     $sql = "SELECT if(MAX( binds.`price` ), MAX( binds.`price`), start_price) AS max_price, step
    //     FROM lots LEFT JOIN binds ON lots.id=binds.lot_id WHERE lots.id=? GROUP BY lots.id ;";
    //     $result = DB::getInstance()->getOne($sql, [$lot_id]);
    //     return $result["max_price"] + $result["step"];
    // }

}
