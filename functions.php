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