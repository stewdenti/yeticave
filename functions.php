<?php

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