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
     * запись в таблицу по свойствам созданным при создании объекта.
     *
     * @return bool true если запись добавлена и false если запись не была добавлена
     * @throws Exception
     */
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

            $sql = "INSERT ".static::tableName()." SET $insert_fields ;";
            $this->id = DB::getInstance()->dataInsertion($sql, $insert_values);

            return $this->id ? true : false;
    }

    /**
     * Обновление записи полученной соответствующей обхекту. Если свойство id не установлено, то запись считается новой
     * и добавляется в таблицу.
     *
     * @return int число обновленных записей
     * @throws Exception
     */
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

    /**
     * Удаляет запись соответствующей объекту.
     * @return bool
     * @throws Exception
     */
    public function delete()
    {
        return DB::getInstance()->dataDelete($this->tableName(),["id"=>$this->id]);
    }
}
