<?php
/**
 * Класс для работы со ставками
 */
class Bind extends BaseRecord {

    public $user_id;
    public $lot_id;
    public $price;
    public $date;

    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName() {
        return 'binds';
    }

    /**
     * возвращает список полей таблицы
     * @return array список полей
     */
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
}
