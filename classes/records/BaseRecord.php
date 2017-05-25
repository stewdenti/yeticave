<?php

abstract class BaseRecord {

    public $id;

    /**
     * @param array $dataFromDb массив вида ['id' => 123, 'name' => 'some name'] пришедший из базы
     */
    public function __construct(array $dataFromDb)
    {
        foreach ($this->dbFields() as $field) {
            if (isset($dataFromDb[$field])) {
                $this->$field = $dataFromDb[$field];
            }
        }
    }

    /**
     * @return string[] названия колонок из базы, метод должен быть определен для каждого типа записей в дочернем классе
     */
    public abstract function dbFields();

    /**
     * @return string название таблицы, из которой выбираем, должно быть переопределено для каждого типа записи
     * @throws Exception
     */
    protected static function tableName()
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
        $className = get_called_class();
        return new $className($result);
    }

    /**
     * Поиск в таблице по ключу, возвращает объект класса, у которого метод вызван
     * @param  string $key ключ для поиска в таблице
     * @param  string $value значения ключа для поиска
     *
     * @return array|null
     */
    protected static function getByKey($key, $value)
    {
        $sql = "SELECT * FROM ".static::tableName()." WHERE $key=?;";
        $result = DB::getInstance()->getOne($sql, [$value]);
        $className = get_called_class();
        return new $className($result);
    }
}