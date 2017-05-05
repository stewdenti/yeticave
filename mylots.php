<?php
include ('functions.php');
include ('arrayLot.php');

session_start();

if (isset($_SESSION["user"])) {
    $header_data = array ("username"=>$_SESSION["user"]);
    $data["username"] = $_SESSION["user"];
} else {
    header("HTTP/1.1 403 Forbidden");
    echo "Доступ закрыт для анонимных пользователей";
    exit();
}




$lot_bind_data = array();

if (!empty($_COOKIE["lot_bind"])) {
    $lot_bind = json_decode($_COOKIE["lot_bind"], true);

    foreach ($lot_bind as $bind_key => $bind_value) {
        foreach ($announcement_list as $key => $value) {
            if ($value["id"] == $bind_key) {
                $lot_item = $value;

                break;
            }
        }

        $lot_bind_data[] = array (
            "rates_id" => $bind_key,
            "rates_title" => $lot_item["title"],
            "rates_img" => $lot_item["URL-img"],
            "rates_category" => $lot_item["category"],
            "rates_price" => $bind_value["cost"],
            "rates_time" => formatTime($bind_value["time"])
        );


    }

}

$data["rates"] = $lot_bind_data;

echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/mylot-main.php", $data);
echo connectTemplates("templates/footer.php", array());



?>