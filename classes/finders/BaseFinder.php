<?php

abstract class BaseFinder
{
    /**
     * возвращает имя таблицы
     * @return string имя таблицы
     * @throws Exception
     */
    protected static function tableName()
    {
        throw new Exception('not implemented');
    }

    /**
     * возвращает имя класса
     *
     * @return string имя класса
     * @throws Exception
     */
    protected static function entityName()
    {
        throw new Exception('not implemented');
    }

    /**
     * Выбор записи по id с созданием объекта класса, у которого этот метод вызван
     * Например User::getById() создаст объект пользователя
     * @param $id
     * @return BaseRecord
     * @throws Exception
     */
    public static function getById($id)
    {
        $sql = "SELECT * FROM ".static::tableName()." WHERE id = ?";
        $result = DB::getInstance()->getOne($sql, [$id]);
        $entityName =  static::entityName();
        return $result ? new $entityName($result): null;

    }

    /**
     * Поиск в таблице по ключу, возвращает объект класса, у которого метод вызван
     * @param  string $key ключ для поиска в таблице
     * @param  string $value значения ключа для поиска
     *
     * @return BaseRecord|null
     */
    protected static function getOneByKey($key, $value)
    {
        $sql = "SELECT * FROM ".static::tableName()." WHERE $key=?;";
        $result = DB::getInstance()->getOne($sql, [$value]);
        $entityName =  static::entityName();
        return $result ? new $entityName($result) : null;
    }

    /**
     * Поиск в таблице по ключу, возвращает объект класса, у которого метод вызван
     * @param string $key ключ для поиска в таблице
     * @param string $value значения ключа для поиска
     * @param string|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return BaseRecord[]
     */
    protected static function getByKey($key, $value, $orderBy = null, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM ".static::tableName()." WHERE $key=?";
        $sql = self::appendOrderByLimitOffset($sql, $orderBy, $limit, $offset);
        return array_map(
            function($c) {
                $entityName =  static::entityName();
                return new $entityName($c);
            },
            DB::getInstance()->getAll($sql, [$value])
        );
    }


    /**
     * Получение всех записей таблицы и возвращает массив объектов класса у которого вызван метод
     * @param null $where
     * @param string|null $orderBy ;
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws Exception
     */
    public static function getAll($where = null, $orderBy = null, $limit = null, $offset = null, $placeHoldersValues = null)
    {
        $sql = "SELECT * FROM ".static::tableName();
        $sql = self::appendWhereCondition($sql,$where);
        $sql = self::appendOrderByLimitOffset($sql, $orderBy, $limit, $offset);
        return array_map(
            function($c) {
                $entityName =  static::entityName();
                return new $entityName($c);
            },
            DB::getInstance()->getAll($sql, $placeHoldersValues)
        );
    }

    /**
     * добавления условий к запросу для сортировки и выбора количества элементов
     *
     * @param string $sql
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return string
     */
    private static function appendOrderByLimitOffset($sql, $orderBy, $limit, $offset) {
        if ($orderBy !== null) {
            $sql .= ' ORDER BY '.$orderBy;
        }
        if ($limit !== null) {
            $sql .= ' LIMIT '.$limit;
        }
        if ($offset !== null) {
            $sql .= ' OFFSET '.$offset;
        }
        return $sql;
    }

    /**
     * добавлние where условия к запросу
     *
     * @param string $sql SQL запрос
     * @param string|null $where условие выборки
     *
     * @return string SQL запрос с условием WHERE
     */
    private static function appendWhereCondition($sql, $where = null)
    {
        if ($where !== null) {
            $sql .= " WHERE ".$where;
        }
        return $sql;
    }

    /**
     * Получение количества записей по условию или без него
     *
     * @param string|null $where  условие для выборки записей
     * @param array $placeHolderData значения для плейсхолдеров в запросе
     * @return int количество записей.
     * @throws Exception
     */
    public static function getAllCount($where = null, $placeHolderData = [])
    {
        $sql = "SELECT COUNT(*) AS number FROM ".static::tableName();
        $sql = self::appendWhereCondition($sql, $where);

        $result = DB::getInstance()->getOne($sql, $placeHolderData);
        return $result["number"] ? (int)$result["number"] : 0;
    }
}
