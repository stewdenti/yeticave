<?php

abstract class BaseRecord {

    protected $id;

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

    public function __get($name) 
    {
        return $this->$name;
    }

    public function __set($name, $value) 
    {
        $this->$name = $value;
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

    public function insert()    
    {
           $sql = ""
    }

    public function update()
    {
    
    }

    public function delete ()
    {
    
    }
}
public static function addNew($data = array())
    {
        $sql = "INSERT lots SET user_id = ?, category_id=?, name=?, description=?, img_path=?,
                start_price=?, step=?, end_date=?, add_date=NOW()";

        $lot_id = DB::getInstance()->dataInsertion($sql, [
            $data["user_id"], $data["category"], $data["lot-name"], $data["message"], $data["URL-img"],
            $data["price"], $data["lot-step"], date("Y:m:d H:i", strtotime($data["lot-date"]))
        ]);

        return $lot_id ?: false;
    }