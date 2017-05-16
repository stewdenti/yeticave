<?php
include "mysql_helper.php";
//Функция подключения шаблонов через буферизацию
function connectTemplates ($filename, $data)
{
    if (file_exists($filename)) {
        foreach ($data as $key => $value){
           $data[$key] = protectXSS($value);
        }
        extract($data);                 //импортирует переменные из массива
        ob_start();                     //ключается буферизация
        include ($filename);            //подключается шаблон
        $content = ob_get_contents();   //в переменную заносится все из буфера
        ob_end_clean();                 //буфер очищается и тключается
        return $content;
    } else {
        return ("");
    }
}

/*Функция  protectXSS() проверяет, является ли аргумент строкой. Если яволяется, то пропускает эту строку через функцию
  htmlspecialchars(), которая заменяет символы тегов на мнимоники(спецсимволы). Если аргумент является массивом, то
  через рекурсию доходит до уровня строки и тогда для изменения строки использует фунцию htmlspecialchars().*/

function protectXSS($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = protectXSS($value);
        }
        return $data;
    } else {
        return htmlspecialchars($data);
    }

}


function formatTime ($date)
{
    //т.к. данные в базе хранятся в datetime формате, то приводим их к формату timestamp
    $time = strtotime($date);
    $td = time() - $time;

    if ($td > 86400) {
        return date("d.m.y в H:i", $time);
    } elseif ($td < 86400 && $td >= 3600){
        $th = date("G", mktime(0, 0, $td));
        if ($th == 1 || $th == 21){
            return $th." час назад";
        } elseif ($th == 2 || $th == 3 || $th == 4 ) {
            return $th." часа назад";
        } else {
            return $th . " часов назад";
        }
    } else {
        return date("i", mktime(0, 0, $td))." минут назад";
    }
}


/**
 * Возвращает минимальную ставку которую должен сделать пользователь
 *
 * @param $link resource link to db
 * @param $lot_id lot id
 * @return mixed возвращает сумму максимльной ставки и шага.
 */
function getMaxBet($link, $lot_id)
{
    $result = 0;

    $sql = "SELECT if(MAX( binds.`price` ), MAX( binds.`price`), start_price) AS max_price, step 
    FROM lots LEFT JOIN binds ON lots.id=binds.lot_id WHERE lots.id=? GROUP BY lots.id ;";
    $result = dataRetrievalAssoc($link, $sql, [$lot_id], true);
    return $result["max_price"] + $result["step"];

}

//функция выводит класс при наличии ошибки
function printInvalidItemClass($errors, $name)
{
    if (isset($errors[$name])) {
        echo "form__item--invalid";
    }
}
//
function printInputItemValue($item, $name)
{
    if (!empty($item[$name])) {
        echo $item[$name];
    }
}
//функция проверяет произведена ли аутентификация на сайте
//возвращает либо данные пользователя из сессии
//либо пустой массив
//либо блокирует доступ к странице
/**
 * @param bool $accessDenied нужно ли блокировать доступ или нет
 * @return array
 */
function requireAuthentication($accessDenied = false)
{
    if (isset($_SESSION["user"])) {
        return array (
            "user_id" => $_SESSION["user"]["id"],
            "username" => $_SESSION["user"]["name"],
            "avatar" => $_SESSION["user"]["avatar_img"]);
    } else {
        $not_auth = array();
    }
    if ($accessDenied) {
         header("HTTP/1.1 403 Forbidden");
         echo "Доступ закрыт для анонимных пользователей";
         exit();
    } else {
        return $not_auth;
    }
}


//Функция для вставки данных, которая возвращает идентификатор последней добавленной записи

function dataInsertion($con, $sql, $unitDataSql)
{
    $sqlReady = db_get_prepare_stmt($con, $sql, $unitDataSql);
    if (!$sqlReady) return false;

    if (mysqli_stmt_execute($sqlReady)) {
        $result = mysqli_stmt_insert_id($sqlReady);

    } else {
        $result = false;
    }
    mysqli_stmt_close($sqlReady);
    return $result;

}

//Функция для обновления данных, которая возвращает количество обновлённых записей.

function dataUpdate($con, $nameTable, $unitUpdatedData, $unitDataConditions)
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

    $sqlReady = db_get_prepare_stmt($con, $sql, $updatingValues);

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
 * осуществляет установку соединения к базе данных по указанным конфигурационным данныи
 * и возвращает либо рерсурс соединения или false
 * @return bool|mysqli
 */
function create_connect()
{
    $link = mysqli_connect("localhost", "root", "", "yeticave_db");
    if ($link) {
        return $link;
    } else {
        return false;
    }
}

/**
 * Функция для получения данных из БД в виде ассоциативного массива,
 * где ключи это названия полей в запросе а значения значения этих полей в соответствующих столбцах 
 *  
 * 
 * @param $con
 * @param $sql
 * @param $unitDataSql
 * @param $oneRow нужно ли возвращать одну запись или все
 * @return array
 */
function dataRetrievalAssoc($con, $sql, $unitDataSql, $oneRow = false )
{
    $resultArray = [];

    $sqlReady = db_get_prepare_stmt($con, $sql, $unitDataSql);

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
 * Получение всех открытых лотов.
 *
 * @param $link
 * @return array
 */
function getAllOpenLots($link)
{
    $sql = "SELECT lots.id, lots.`name`,`categories`.`name` AS 'category', 
    if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price, 
    if (COUNT(binds.price) > 0, COUNT(binds.price), 0) as binds_number, 
    `img_path`, `end_date`   
    FROM lots JOIN `categories` ON lots.`category_id` = `categories`.id 
    LEFT JOIN binds ON lots.id = binds.`lot_id` WHERE `end_date` > NOW()
    GROUP BY lots.id ORDER BY lots.add_date DESC
    LIMIT 9;";
    $lots_data = dataRetrievalAssoc($link, $sql, []);
    return $lots_data;
}

/**
 * Получение всех открытых лотов заданной категории
 *
 * @param $link
 * @param $category_id
 * @return array
 */
function getLotsByCategoryId($link, $category_id)
{
    $id = protectXSS($category_id);
    $sql = "SELECT lots.id, lots.`name`,`categories`.`name` AS 'category', 
    if (MAX( binds.`price` ), MAX( binds.`price`), start_price) as price, 
    if (COUNT(binds.price) > 0, COUNT(binds.price), 0) as binds_number, 
    `img_path`, `end_date`   
    FROM lots JOIN `categories` ON lots.`category_id` = `categories`.id 
    LEFT JOIN binds ON lots.id = binds.`lot_id` 
    WHERE `end_date` > NOW() AND category_id = ?
    GROUP BY lots.id ORDER BY lots.add_date DESC
    LIMIT 9;    
    ";
    $lots_data = dataRetrievalAssoc($link, $sql, [$id]);
    return $lots_data;
}

/**
 * Получение всех категорий
 *
 * @param $link
 * @return array
 */
function getAllCategories($link)
{
    $sql = "SELECT `id`, `name` FROM categories;";
    $categories = dataRetrievalAssoc($link, $sql, []);
    return $categories;
}

/**
 * получение данных пользователя из БД по заданному ключу и значению
 *
 * @param $link
 * @param string $key
 * @param string $value
 * @return array|bool
 */
function getUserByKey($link, $key="email", $value="")
{
    $sql = "SELECT * FROM users WHERE $key=?;";
    $user = dataRetrievalAssoc($link, $sql, [$value], true);
    if ($user) {
        return $user;
    } else{
        return false;
    }
}

/**
 * получение лота по определенному ключу и значению
 *
 * @param $link
 * @param string $key
 * @param string $value
 * @return array|bool
 */
function getLotByKey($link, $key="id", $value="")
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

    $lot_data = dataRetrievalAssoc($link,$sql,[$value],true);

    if ($lot_data) {
        return $lot_data;
    } else {
        return false;
    }
}

/**
 * Получение всех ставок для выбранного лота
 *
 * @param $link
 * @param $id
 * @return array
 */
function getBetsByLotID($link, $id)
{
    $sql = "SELECT users.name AS name, binds.price, binds.date FROM binds 
    JOIN users ON binds.user_id=users.id 
    JOIN lots ON lots.id = binds.lot_id
    WHERE binds.lot_id=? AND binds.price != lots.start_price
    ORDER BY price DESC";
    $bets = dataRetrievalAssoc($link, $sql, [$id]);
    return $bets;
}

/**
 * осуществляется проверка разрешено ли пользователю сделать ставку для лота (возвращает true )
 * или нет (false)
 *
 * @param $link
 * @param $lot_id
 * @param $user_id
 * @return bool
 */
function  isMakeBindAllowed($link, $lot_id, $user_id)
{
    $sql = "SELECT COUNT(price) AS number FROM binds 
    WHERE lot_id=? AND user_id=? ";

    $res = dataRetrievalAssoc($link, $sql, [ $lot_id, $user_id], true);
    if ($res["number"] > 0 ) {
        return false;
    } else {
        return true;
    }
}

/**
 * Получение все лотов для которых пользователь сделал ставку
 *
 * @param $link
 * @param $user_id
 * @return array
 */
function getAllBindedLotsByUser ($link, $user_id)
{
    $sql = "SELECT lots.id, lots.name, lots.img_path, categories.name AS category, binds.price, binds.date, lots.end_date 
    FROM lots
    JOIN binds on binds.lot_id=lots.id
    JOIN categories on lots.category_id=categories.id
    WHERE binds.user_id=? AND `end_date` > NOW()";
    $result = dataRetrievalAssoc($link, $sql, [$user_id]);
    return $result;
}

/**
 * Добавляет новый лот в базу данных
 *
 * @param $link
 * @param array $data
 * @return bool|mixed
 */
function addNewLot($link, $data=array())
{
    $sql = "INSERT lots SET user_id = ?, category_id=?, name=?, description=?, img_path=?,
            start_price=?, step=?, end_date=?, add_date=NOW()";

    $lot_id = dataInsertion($link, $sql, [
        $data["user_id"], $data["category"], $data["lot-name"], $data["message"], $data["URL-img"],
        $data["price"], $data["lot-step"], date("Y:m:d H:i",strtotime($data["lot-date"]))
    ]);

    if ($lot_id) {
        return $lot_id;
    } else {
        return false;
    }
}

/**
 * Добавляет нового пользователя в базу данных
 *
 * @param $link
 * @param array $data
 * @return bool
 */
function addNewUser($link, $data = array())
{
    $sql = "INSERT users SET email = ?, password = ?, name = ?,
            contacts = ?, avatar_img = ?";
    $unitDataSql = [];
    foreach ($data as $value) {
        $unitDataSql[] = $value;
    }
    $user_id = dataInsertion($link, $sql,  $unitDataSql);
    if ($user_id) {
        return true;
    } else {
        return false;
    }
}

/**
 * Добавляет новую ставку для лота в базу данных
 *
 * @param $link
 * @param array $data
 * @return bool
 */
function addNewBind($link, $data = array())
{
    $sql = "INSERT binds SET user_id=?, lot_id=?, price=?, date=NOW();";
    
    $result = dataInsertion($link, $sql, [$data["user_id"], $data["lot_id"], $data["cost"]]);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}