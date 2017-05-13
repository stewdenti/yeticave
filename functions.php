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

// возвращает оставшееся время до начала следующих суток
function getLotTimeRemaining()
{
    // временная метка для полночи следующего дня
    $tomorrow = strtotime('tomorrow midnight');
    // временная метка для настоящего времени
    $now = time();
    return date("H:i", mktime(0, 0, $tomorrow - $now));
}

function getCategories($link)
{
    $sql = "SELECT `id`, `name` FROM categories;";
    $categories = dataRetrieval($link, $sql, []);
    return $categories;

}


function getLots ($link,$id=false)
{
    if ($id && is_numeric($id)) {
        $id = protectXSS($id);
        $sql = "SELECT lots.id, lots.`name`, `categories`.`name` AS 'category', MAX( binds.`price`) AS price, `img_path`, `end_date`  
FROM lots
JOIN `categories`
ON lots.`category_id` = `categories`.id
JOIN binds
ON lots.id = binds.`lot_id`
WHERE `end_date` > NOW() AND `winner`=0 AND category_id = ?
GROUP BY lots.id
ORDER BY lots.add_date DESC;";
        $lots_list = dataRetrieval($link, $sql, [$id]);
    } else {
        $sql = "SELECT lots.id, lots.`name`, `categories`.`name` AS 'category', MAX( binds.`price`) AS price, `img_path`, `end_date`  
FROM lots
JOIN `categories`
ON lots.`category_id` = `categories`.id
JOIN binds
ON lots.id = binds.`lot_id`
WHERE `end_date` > NOW() AND `winner`=0
GROUP BY lots.id
ORDER BY lots.add_date DESC;";
        $lots_list = dataRetrieval($link, $sql, []);
    }

    $lots_data = [];
    foreach ($lots_list as $value) {
        $lots_data[] = array (
            "id"=>$value[0],
            "name"=>$value[1],
            "category"=>$value[2],
            "price"=>$value[3],
            "URL-img"=>$value[4],
            "time-remaining"=>$value[5]
        );
    }


    return $lots_data;

}
//поиск пользователя по email
//$find_value искомое значение
//$search_in_key переменная, указывающая в каком ключе массива искать значение
//$allUsers переменная, укзывающая в каком массиве происходит поиск
function searchUserByKey($find_value, $search_in_key, $allUsers)
{
    $result = null;
    foreach ($allUsers as $user) {
        if ($user[$search_in_key] == $find_value) {
            $result = $user;
            break;
        }
    }
    return $result;
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

// Возвращает максимальную ставку по лоту в виде числа
function getMaxBet($link,$id)
{

    $result = 0;

    $sql = "SELECT MAX(price) AS max_price, step FROM binds JOIN lots ON lots.id=binds.lot_id WHERE binds.lot_id=? GROUP BY lots.id;";
    $result = dataRetrieval($link, $sql, [$id]);

    return $result[0][0]+$result[0][1];

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
function requireAuthentication()
{
    if (isset($_SESSION["user_name"])) {
        
        
        return array ("username"=>$_SESSION["user_name"],
            "avatar" => $_SESSION["avatar_img"]);
    } else {
        header("HTTP/1.1 403 Forbidden");
        echo "Доступ закрыт для анонимных пользователей";
        exit();
    }
}

//поиск лота по id
function findLotById($array_search_in, $id)
{
    foreach ($array_search_in as $key => $value) {
        if ($value["id"] == $id) {
            return $value;
        }
    }

    return null;
}

//Функция для получения данных. Функция возвращает простой, двумерный массив с данными из БД
function dataRetrieval($con, $sql, $unitDataSql)
{
    $resultArray = [];

    $sqlReady = db_get_prepare_stmt($con, $sql, $unitDataSql);

    if (!$sqlReady) return $resultArray;

    if (mysqli_stmt_execute($sqlReady)) {
        $result = mysqli_stmt_get_result($sqlReady);
    } else {
        $result = false;
    }

    if ($result) {

        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
            $resultArray[] = $row;
        }
    }
    mysqli_stmt_close($sqlReady);
    return $resultArray;

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

    if (!$sqlReady) return false;
    if (mysqli_stmt_execute($sqlReady)) {
        $result = mysqli_stmt_affected_rows($sqlReady);

    } else {
        $result = false;
    }
    mysqli_stmt_close($sqlReady);
    return $result;

}

function create_connect() {
    $link = mysqli_connect("localhost", "root", "", "yeticave_db");

    if ($link) return $link; else return false;


}

