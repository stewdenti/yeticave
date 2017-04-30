<?php 
include ('functions.php');
include ('arrayLot.php');
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

$data = array (
    "categories_equipment" => $categories_equipment,
    "announcement_list"    => $announcement_list,
    "lot_time_remaining" => $lot_time_remaining,
);


echo connectTemplates("templates/header.php", array());
echo connectTemplates("templates/main.php", $data);
echo connectTemplates("templates/footer.php", array());
?>
