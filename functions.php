<?php

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
    } else if (is_string($data)) {
        return htmlspecialchars($data);
    }
    return $data;
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

