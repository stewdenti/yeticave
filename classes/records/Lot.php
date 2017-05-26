<?php
/**
 * Класс для работы с лотами
 */
class Lot extends BaseRecord {

    protected $user_id;
    protected $category_id;
    protected $name;
    protected $description;
    protected $img_path;
    protected $start_price;
    protected $step;
    protected $end_date;
    protected $add_date;
    protected $winner;

    protected static function tableName() {
        return 'lots';
    }

    public function dbFields()
    {
        return [
            'id', 'user_id', 'category_id', 'name', 'description', 'img_path',
            'start_price', 'step', 'end_date', 'add_date', 'winner'
        ];
    }

    /**
     * Возвращает категорию лота
     * @return Category
     */
    public function getCategory() {
        /** @var Category $result */
        $result = CategoryFinder::getById($this->category_id);
        return $result;
    }

    /**
     * Возвращает ставки для лота
     * @return Bind[]
     */
    public function getBinds() {
        return BindFinder::getByLotID($this->id);
    }

    /**
     * Возвращает последнюю ставку пользователя $userId по этому лоту
     * @param $userId
     * @return Bind|null
     */
    public function getLastBindByUserId($userId) {
       return BindFinder::getLastBindByUserIdAndLotId($this->lot_id, $user_id);
    }

    /**
     * Возвращает текущее значение ставки в виде числа
     * @return int
     */
    public function getCurrentBet() {
        $bets = $this->getBinds();
        $bets = array_map(function(Bind $b){return $b->price;}, $bets);
        if (empty($bets)) {
            return $this->start_price;
        } else {
            return max($bets);
        }
    }

    /**
     * Возвращает минимальное значение возможной ставки для лота
     * @return int
     */
    public function getMinNextBet() {
        $bets = $this->getBinds();
        $bets = array_map(function(Bind $b){return $b->price;}, $bets);
        if (empty($bets)) {
            return $this->start_price;
        } else {
            return max($bets) + $this->step;
        }
    }

    // /**
    //  * Возвращает лоты пользователя
    //  * @param $userId
    //  * @return Lot[]
    //  * @throws Exception
    //  */
    // public function getByUserId($userId) {
    //     $sql = "SELECT * FROM ".static::tableName()." WHERE user_id = ? ORDER BY add_date DESC LIMIT 9;";
    //     return array_map(
    //         function($l) {
    //             return new Lot($l);
    //         },
    //         DB::getInstance()->getAll($sql, [$userId])
    //     );
    // }

    // /**
    //  * Получение списка лотов для заданной категории
    //  *
    //  * @param integer $categoryId id категории по которой нужно найти лоты
    //  * @return Lot[] массив всех лотов для заданной категории
    //  */
    // public static function getByCategoryId($categoryId)
    // {
    //     $categoryId = protectXSS($categoryId);
    //     $sql = "SELECT * FROM ".static::tableName()." WHERE end_date > NOW() AND winner is NULL AND category_id = ? ORDER BY add_date DESC LIMIT 9;";
    //     return array_map(
    //         function($l) {
    //             return new Lot($l);
    //         },
    //         DB::getInstance()->getAll($sql, [$categoryId])
    //     );
    // }

    // /**
    //  *  получение всех открытых лотов
    //  *
    //  * @return Lot[] список всех лотов
    //  */
    // public static function getAllOpened()
    // {
    //     $sql = "SELECT * FROM ".static::tableName()." WHERE end_date > NOW() and winner is NULL ORDER BY add_date DESC LIMIT 9;";
    //     return array_map(
    //         function($l) {
    //             return new Lot($l);
    //         },
    //         DB::getInstance()->getAll($sql, [])
    //     );
    // }

    /**
     * Добавление записи в таблицу о лоте
     * @param array $data данные о лоте для добавления записи в таблицу
     * @return int|bool
     */
    // public static function addNew($data = array())
    // {
    //     $sql = "INSERT lots SET user_id = ?, category_id=?, name=?, description=?, img_path=?,
    //             start_price=?, step=?, end_date=?, add_date=NOW()";

    //     $lot_id = DB::getInstance()->dataInsertion($sql, [
    //         $data["user_id"], $data["category"], $data["lot-name"], $data["message"], $data["URL-img"],
    //         $data["price"], $data["lot-step"], date("Y:m:d H:i", strtotime($data["lot-date"]))
    //     ]);

    //     return $lot_id ?: false;
    // }
}
