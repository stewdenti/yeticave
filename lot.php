<?php
include ('functions.php');
include ('arrayLot.php');
// ставки пользователей, которыми заполняется  таблица
$bets = [
         ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
         ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
         ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')],
         ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime( '-' . rand(25, 50) .' hour')]
         ];

session_start();
$data = array (
    "bets" => $bets,
//    "lot_item" => $lot_item,
);


if (isset($_SESSION["user"])) {
    $header_data = array ("username"=>$_SESSION["user"]);
    $data["username"] = $_SESSION["user"];
} else {
    $header_data = array();
}



/**
 * @param $time
 */
function formatTime ($time)
{
    $td = time()- $time;

    if ($td > 86400){
        return date("d.m.y в H:i", $time);
    } elseif ($td < 86400 && $td >= 3600){
        $th = date("G", mktime(0, 0, $td));
        if ($th == 1 || $th == 21){
            return $th." час назад";
        } elseif ($th == 2 || $th == 3 || $th == 4 ) {
            return $th." часа назад";
        }else {
            return $th . " часов назад";
        }
    } else {
        return date("i", mktime(0, 0, $td))." минут назад";
    }
}
$lot_item = "";


//цикл поиск запрошенного лота

foreach ($announcement_list as $key => $value) {
    if ($value["id"] == $_GET["id"]) {
        $lot_item = $value;
        break;
    }
}



if ($lot_item == "") {
    header("HTTP/1.1 404 Not Found");
    echo "<h1>404 Страница не найдена</h1>";
    exit ();
} else {
    $data["lot_item"] = $lot_item;
}



echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/main-lot.php", $data);
echo connectTemplates("templates/footer.php", array());

?>




