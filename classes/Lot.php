<?php
/**
 * Класс для работы с лотами
 */
class Lot {

    /**
     * Получение списка лотов для заданной категории
     *
     * @param integer $category_id id категории по которой нужно найти лоты
     * @return array массив всех лотов для заданной категории
     */
    public static function getByCategoryId($category_id)
    {
        $id = protectXSS($category_id);
        $sql = "SELECT lots.id, lots.`name`,`categories`.`name` AS 'category',
        if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price,
        if (COUNT(binds.price) > 0, COUNT(binds.price), 0) as binds_number,
        `img_path`, `end_date`
        FROM lots JOIN `categories` ON lots.`category_id` = `categories`.id
        LEFT JOIN binds ON lots.id = binds.`lot_id`
        WHERE `end_date` > NOW() AND winner is NULL AND category_id = ?
        GROUP BY lots.id ORDER BY lots.add_date DESC
        LIMIT 9;
        ";
        $lots_data = DB::getAll($sql, [$id]);
        return $lots_data;
    }

    /**
     *  получение всех открытых лотов
     *
     * @return array список всех лотов
     */
    public static function getAllOpened()
    {
        $sql = "SELECT lots.id, lots.`name`,`categories`.`name` AS 'category',
        if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price,
        if (COUNT(binds.price) > 0, COUNT(binds.price), 0) as binds_number,
        `img_path`, `end_date`
        FROM lots JOIN `categories` ON lots.`category_id` = `categories`.id
        LEFT JOIN binds ON lots.id = binds.`lot_id` WHERE `end_date` > NOW() and winner is NULL
        GROUP BY lots.id ORDER BY lots.add_date DESC
        LIMIT 9;";
        $lots_data = DB::getAll($sql, []);
        return $lots_data;
    }

    /**
     * получение массива данных о лоте по ключу
     *
     * @param string $key имя поля по которому осуществлять поиск
     * @param string $value значение поля по которому осуществлять поиск
     * @return array массив с данным о лоте
     */
    public static function getByKey($key = "id", $value = "")
    {
        $sql = "SELECT lots.id, lots.`name`, `img_path`, `categories`.`name` AS 'category',
         if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price,
        description, step, start_price, end_date
        FROM lots
        JOIN `categories`
        ON lots.`category_id` = `categories`.id
        LEFT JOIN binds ON lots.id = binds.`lot_id`
        WHERE lots.$key = ?
        GROUP BY lots.id";

        $lot_data = DB::getOne($sql, [$value]);

        if ($lot_data) {
            return $lot_data;
        } else {
            return false;
        }
    }

    /**
     * получение списка лотов, для которых пользователь сделал ставки
     *
     * @param  integer $user_id id пользователя
     *
     * @return array список всех лотов для  которых сделал пользователь ставки
     */
    public static function getAllBindsByUser ($user_id)
    {
        $sql = "SELECT lots.id, lots.name, lots.img_path, categories.name AS category, binds.price, binds.date, lots.end_date
        FROM lots
        JOIN binds on binds.lot_id=lots.id
        JOIN categories on lots.category_id=categories.id
        WHERE binds.user_id=? AND `end_date` > NOW()";
        $result = DB::getAll($sql, [$user_id]);
        return $result;
    }

    /**
     * Добавление записи в таблицу о лоте
     *
     * @param array $data данные о лоте для добавления записи в таблицу
     */
    public static function addNew($data = array())
    {
        $sql = "INSERT lots SET user_id = ?, category_id=?, name=?, description=?, img_path=?,
                start_price=?, step=?, end_date=?, add_date=NOW()";

        $lot_id = DB::dataInsertion($sql, [
            $data["user_id"], $data["category"], $data["lot-name"], $data["message"], $data["URL-img"],
            $data["price"], $data["lot-step"], date("Y:m:d H:i", strtotime($data["lot-date"]))
        ]);

        if ($lot_id) {
            return $lot_id;
        } else {
            return false;
        }
    }
}
