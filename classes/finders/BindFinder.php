<?php

class BindFinder extends BaseFinder
{

    protected static function tableName()
    {
        return "binds";
    }

    protected static function entityName()
    {

        return "Bind";
    }

    public static function getByLotID($lotId)
    {
        $sql = "SELECT * FROM binds WHERE binds.lot_id=? ORDER BY price DESC";
        return array_map(
            function($b) {
                $entity = self::entityName();
                return new $entity($b);
            },
            DB::getInstance()->getAll($sql, [protectXSS($lotId)])
        );
    }

    public static function getByUserId($userId)
    {
        $sql = "SELECT * FROM binds WHERE user_id=? ORDER BY price DESC";
        return array_map(
            function($b) {
                $entity = self::entityName();
                return new $entity($b);
            },
            DB::getInstance()->getAll($sql, [protectXSS($userId)])
        );
    }

    public static function getLastBindByUserIdAndLotId($userId, $lot_id) {
        $sql = "SELECT * FROM ".self::tableName()." WHERE user_id = ? AND lot_id = ? ORDER BY price DESC LIMIT 1";
        $result = DB::getInstance()->getOne($sql, [$userId, $lot_id]);
        $entity = self::entityName();
        return $result ? new $entity($result) : null;
    }

    public static function canMakeBet($lot_id, $user_id)
    {
        $bets = self::getByLotID($lot_id);  // первая в массиве = последняя по времени
        if (empty($bets)) {
            return true;
        }
        return $bets[0]->user_id != $user_id;
    }
}
