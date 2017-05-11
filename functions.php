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

function getCategories()
{
    return ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
}


function getLots ()
{
    return array(
        "announcement_1" => array('title' => "2014 Rossignol District Snowboard",
            'category' => "Доски и лыжи",
            'price' => "10999",
            'URL-img' => "/img/lot-1.jpg",
            'id' => "0"),
        "announcement_2" => array('title'=>"DC Ply Mens 2016/2017 Snowboard",
            'category' => "Доски и лыжи",
            'price' => "159999",
            'URL-img' => "/img/lot-2.jpg",
            'id' => "1"),
        "announcement_3" => array('title'=>"Крепления Union Contact Pro 2015 года размер L/XL",
            'category' => "Крепления",
            'price' => "8000",
            'URL-img' => "/img/lot-3.jpg",
            'id' => "2"),
        "announcement_4" => array('title'=>"Ботинки для сноуборда DC Mutiny Charocal",
            'category' => "Ботинки",
            'price' => "10999",
            'URL-img' => "/img/lot-4.jpg",
            'id' => "3"),
        "announcement_5" => array('title'=>"Куртка для сноуборда DC Mutiny Charocal",
            'category' => "Одежда",
            'price' => "7500",
            'URL-img' => "/img/lot-5.jpg",
            'id' => "4"),
        "announcement_6" => array('title'=>"Маска Oakley Canopy",
            'category' => "Разное",
            'price' => "5400",
            'URL-img' => "/img/lot-6.jpg",
            'id' => "5")
    );
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

function formatTime ($time)
{
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
function getMaxBet($search_in)
{
    $result = 0;
    foreach ($search_in as $bet){
        if ($bet['price'] > $result) {
            $result = $bet['price'];
        }
    }
    return $result;

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
    if (isset($_SESSION["user"])) {
        $header_data = array ("username"=>$_SESSION["user"]);
        return $_SESSION["user"];
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

//Функция для получения данных
function dataRetrieval($con, $sql, $unit_data_sql)
{
    $result_array = [];

    $sql_ready = db_get_prepare_stmt($con, $sql, $unit_data_sql);
    if (!mysqli_stmt_execute($sql_ready)) {

        return $result_array;
    }
    $result = mysqli_stmt_get_result($sql_ready);
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
            $result_array[] = $row;
        }
        return $result_array;
    } else {

        return $result_array;
    }
}

//Функция для вставки данных
function dataInsertion($con, $sql, $unit_data_sql)
{
    $sql_ready = db_get_prepare_stmt($con, $sql, $unit_data_sql);
    if (mysqli_stmt_execute($sql_ready)) {
       return  mysqli_stmt_insert_id($sql_ready);
    } else {
      return false;
    }

}

//Функция для обновления данных

function dataUpdate($con, $name_table, $unit_updated_data, $unit_data_conditions)
{
    $updating_fields = "";
    $updating_values = [];

    foreach ($unit_updated_data as $key => $value) {
        $updating_fields .= "`$key`=?, ";
        $updating_values[] = $value;
    }

    $updating_fields = substr($updating_fields, 0, -2);

    $where_field = array_keys($unit_data_conditions)[0];
    $updating_values[] =array_values($unit_data_conditions)[0];

    $sql = "UPDATE `$name_table` SET $updating_fields WHERE `$where_field`=?;";

    $sql_ready = db_get_prepare_stmt($con, $sql, $updating_values);

    if (mysqli_stmt_execute($sql_ready)) {
        return  mysqli_stmt_affected_rows($sql_ready);
    } else {
        return false;
    }

}
