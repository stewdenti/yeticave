<?php

class BindFinder extends BaseFinder
{

    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName()
    {
        return "binds";
    }

    /**
     * возвращает имя класса
     *
     * @return string имя класса
     */
    protected static function entityName()
    {
        return "Bind";
    }

    /**
     * получение всех ставок для лота. Возвращает массив объектов Bind
     * @param int $lotId
     * @return Bind[]
     */
    public static function getByLotID($lotId)
    {
        return self::getByKey('lot_id', $lotId, 'price DESC');
    }

    /**
     * получение всех ставок для лота
     * @param int $userId
     * @return Bind[]
     */
    public static function getByUserId($userId)
    {
        return self::getByKey('user_id', $userId, 'price DESC');
    }

    /**
     * Получение максимальной ставик для лота
     * @param integer $lot_id id лота для которого нужно найти максимальную ставку
     *
     * @return integer минимальная ставка которую должен сделать пользователь
     */
    public static function getLastBindByUserIdAndLotId($userId, $lot_id) {
        $sql = "SELECT * FROM ".self::tableName()." WHERE user_id = ? AND lot_id = ? ORDER BY price DESC LIMIT 1";
        $result = DB::getInstance()->getOne($sql, [$userId, $lot_id]);
        $entity = self::entityName();
        return $result ? new $entity($result) : null;
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
        // проверяем не истекла ли дата лота. Если истекла, то ставки делать нельзя
        if (!LotFinder::getById($lot_id)->isOpenForBet()) {
            return false;
        }

        $bets = self::getByLotID($lot_id);  // первая в массиве = последняя по времени
        if (empty($bets)) {
            return true;
        }
        
        return $bets[0]->user_id != $user_id;
    }
}
