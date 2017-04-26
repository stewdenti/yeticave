<?php include ('functions.php'); ?>
<?php
// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');
// записать в эту переменную оставшееся время в этом формате (ЧЧ:ММ)
$lot_time_remaining = "00:00";
// временная метка для полночи следующего дня
$tomorrow = strtotime('tomorrow midnight');
// временная метка для настоящего времени
$now = time();
// далее нужно вычислить оставшееся время до начала следующих суток и записать его в переменную $lot_time_remaining
// ...
$lot_time_remaining = date("H:i", mktime(0, 0, $tomorrow - $now));
$categories_equipment = array("Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное");
$announcement_list = array("announcement-1" => array('title'=>"2014 Rossignol District Snowboard",
    'category'=>"Доски и лыжи",
    'price'=>"10999",
    'URL-img'=>"/img/lot-1.jpg"),
    "announcement_2" => array('title'=>"DC Ply Mens 2016/2017 Snowboard",
        'category'=>"Доски и лыжи",
        'price'=>"159999",
        'URL-img'=>"/img/lot-2.jpg"),
    "announcement_3" => array('title'=>"Крепления Union Contact Pro 2015 года размер L/XL",
        'category'=>"Крепления",
        'price'=>"8000",
        'URL-img'=>"/img/lot-3.jpg"),
    "announcement_4" => array('title'=>"Ботинки для сноуборда DC Mutiny Charocal",
        'category'=>"Ботинки",
        'price'=>"10999",
        'URL-img'=>"/img/lot-4.jpg"),
    "announcement_5" => array('title'=>"Куртка для сноуборда DC Mutiny Charocal",
        'category'=>"Одежда",
        'price'=>"7500",
        'URL-img'=>"/img/lot-5.jpg"),
    "announcement_6" => array('title'=>"Маска Oakley Canopy",
        'category'=>"Разное",
        'price'=>"5400",
        'URL-img'=>"/img/lot-6.jpg"),
);
$data = array (
    "categories_equipment" => $categories_equipment,
    "announcement_list"    => $announcement_list,

);

echo connectTemplates("templates/header.php",array());
echo connectTemplates("templates/main.php",$data);
echo connectTemplates("templates/footer.php",array());
?>
