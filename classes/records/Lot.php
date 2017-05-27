<?php
/**
 * Класс для работы с лотами
 */
class Lot extends BaseRecord {

    public $user_id;
    public $category_id;
    public $name;
    public $description;
    public $img_path;
    public $start_price;
    public $step;
    public $end_date;
    public $add_date;
    public $winner;

    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName() {
        return 'lots';
    }

    /**
     * возвращает список полей таблицы
     * @return array список полей
     */

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

}
