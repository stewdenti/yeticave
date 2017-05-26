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
            $insert_fields = "";
            $insert_values = [];
            foreach ($this->dbFields() as $field) {
                if (isset($this->$field)) {
                    $insert_fields .= "".$field." = ?, ";
                    $insert_values[] = $this->$field;
                } 
            }
            $insert_fields = substr($insert_fields, 0, -2);

            $sql = "INSERT ".static::tableName()." SET $insert_fields;";

            $this->id = DB::getInstance()->dataInsertion($sql, $insert_values);

            return $result ? true : false;
    }

    public function update()
    {
            if (!isset($this->id)) {
                $this->insert();
            } else {
                $update_data = [];
                foreach ($this->dbFields() as $field) {
                    if (isset($this->$field)) {
                        $update_data[$field] = $this->$field;                                       
                    }
                }                
                $update_result = DB::getInstance()->dataUpdate($this->tableName(), $update_data, ["id"=>$this->id]);
                return $update_result;
            }

            
    }

    public function delete()
    {
        return DB::getInstance()->dataDelete($this->tableName(),["id"=>$this->id]);
    }
}
