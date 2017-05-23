<?php

/**
 * Класс для работы с базой данных
 *
 */
class DB {
    /**
     * свойство для хранения ресурса соединения до базы данных
     * @var null
     */
	private $link = null;
    /**
     * Хранение текста сообщения последней ошибки
     * @var null
     */
    private $error = null;

    /**
     * статическая переменная для хранения объекта соединения с базой данных
     * @var null
     */
    private static $_instance = null;

    /**
     * Конструктор объекта для работы с базой данных
     *
     */
    private function __construct()
    {
        $this->link = mysqli_connect("localhost", "root", "", "yeticave_db");
        if (!$this->link) {
            $this->error = mysqli_connect_error();
        }
    }

    /**
     *  Десктруктор для разрыва соедиения в случае уничтожения объекта
     *
     */
    private function __destructor()
    {
        mysqli_close($this->link);
    }

    /**
     *  функция получения последней ошибки при работе с базой данных
     *
     * @return [type]
     */
    public function getlastError() {
        return $this->error;
    }

    /**
     * Метод для получения объекта соединения с базой данных.
     * Если объект не существует,то создается новый и помещяется
     * в статичную переменную
     *
     * @return DB instance
     */
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
        return self::$_instance;
   	}

    /**
     * Получение данных в виде одной записи
     *
     * @param string $sql строка запроса с метками
     * @param  array $unitDataSql данные для запроса
     *
     * @return array ассоциативный массив
     */
    public static function getOne($sql, $unitDataSql)
    {
        return self::dataRetrievalAssoc($sql, $unitDataSql,$oneRow = True);
    }

    /**
     * Получение всех записей в виде массива ассоциативных массивов
     *
     * @param string $sql строка запроса с метками
     * @param array $unitDataSql данные для запроса
     *
     * @return array массив ассоциативных массивов
     */
    public static function getAll($sql, $unitDataSql)
    {
        return self::dataRetrievalAssoc($sql, $unitDataSql,$oneRow = False);
    }

    /**
     * Получение записей согласно запросу
     *
     * @param string $sql строка запроса с метками
     * @param array $unitDataSql данные для запроса
     * @param booleans $oneRow вернуть в виде ассоциативного массива одной записи или
     *                         массив ассоциативных массивов
     * @return array результат запроса
     */
   	protected static function dataRetrievalAssoc($sql, $unitDataSql, $oneRow = false )
	{
	    $resultArray = [];

	    $sqlReady = self::db_get_prepare_stmt($sql, $unitDataSql);

	    if (!$sqlReady) {
	        return $resultArray;
	    }

	    if (mysqli_stmt_execute($sqlReady)) {
	        $result = mysqli_stmt_get_result($sqlReady);
	    } else {
	        $result = false;
	    }

	    if ($result) {
	        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	            $resultArray[] = $row;
	        }
	    }

	    mysqli_stmt_close($sqlReady);

	    if ($oneRow && count($resultArray) == 1) {
	        return $resultArray[0];
	    } else {
	        return $resultArray;
	    }
	}

    /**
     *  Добавление записей в базу данных
     *
     * @param string $sql строка запроса с метками
     * @param array $unitDataSql данные для запроса
     *
     * @return integer результат записи в таблицу. id записи.
     */
	public static function dataInsertion($sql, $unitDataSql)
	{
	    $sqlReady = self::db_get_prepare_stmt($sql, $unitDataSql);
	    if (!$sqlReady) {
	        return false;
	    }

	    if (mysqli_stmt_execute($sqlReady)) {
	        $result = mysqli_stmt_insert_id($sqlReady);
	    } else {
	        $result = false;
	    }
	    mysqli_stmt_close($sqlReady);
	    return $result;

	}

    /**
     * @param string $nameTable имя таблицы для обновления данных
     * @param array $unitUpdatedData массив данных для обновления записей
     * @param array $unitDataConditions массив данных для условия where
     *
     * @return integer количество обновленных записей
     */
	public static function dataUpdate($nameTable, $unitUpdatedData, $unitDataConditions)
	{
	    $updatingFields = "";
	    $updatingValues = [];

	    foreach ($unitUpdatedData as $key => $value) {
	        $updatingFields .= "`$key`=?, ";
	        $updatingValues[] = $value;
	    }

	    $updatingFields = substr($updatingFields, 0, -2);

	    $whereField = array_keys($unitDataConditions)[0];
	    $updatingValues[] = array_values($unitDataConditions)[0];

	    $sql = "UPDATE `$nameTable` SET $updatingFields WHERE `$whereField`=?;";

	    $sqlReady = self::db_get_prepare_stmt($sql, $updatingValues);

	    if (!$sqlReady) {
	        return false;
	    }
	    if (mysqli_stmt_execute($sqlReady)) {
	        $result = mysqli_stmt_affected_rows($sqlReady);

	    } else {
	        $result = false;
	    }
	    mysqli_stmt_close($sqlReady);
	    return $result;

	}

    /**
     * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
     *
     *
     * @param string $sql SQL запрос с плейсхолдерами вместо значений
     * @param array $data Данные для вставки на место плейсхолдеров
     *
     * @return mysqli_stmt Подготовленное выражение
     */
    protected static function db_get_prepare_stmt($sql, $data = [])
    {
        $db = self::getInstance();
        $stmt = mysqli_prepare($db->link, $sql);

        if ($data) {
            $types = '';
            $stmt_data = [];

            foreach ($data as $value) {
                $type = null;

                if (is_int($value)) {
                    $type = 'd';
                }
                else if (is_string($value)) {
                    $type = 's';
                }
                else if (is_double($value)) {
                    $type = 'd';
                }

                if ($type) {
                    $types .= $type;
                    $stmt_data[] = $value;
                }
            }

            $values = array_merge([$stmt, $types], $stmt_data);

            $func = 'mysqli_stmt_bind_param';
            $func(...$values);
        }

        return $stmt;
    }


}
