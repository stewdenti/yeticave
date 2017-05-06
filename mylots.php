<?php
include ('functions.php');


session_start();

$header_data['username'] = requireAuthentication();

$lot_bind_data = array();

if (!empty($_COOKIE["lot_bind"])) {
    $lot_bind = json_decode($_COOKIE["lot_bind"], true);

    foreach ($lot_bind as $bind_key => $bind_value) {
        if ($lot_item = findLotById(getLots(), $bind_key)) {
            $lot_bind_data[] = array(
                "rates_id" => $bind_key,
                "rates_title" => $lot_item["title"],
                "rates_img" => $lot_item["URL-img"],
                "rates_category" => $lot_item["category"],
                "rates_price" => $bind_value["cost"],
                "rates_time" => formatTime($bind_value["time"])
            );
        }

    }

}

$data["rates"] = $lot_bind_data;

echo connectTemplates("templates/header.php", $header_data);
echo connectTemplates("templates/mylot-main.php", $data);
echo connectTemplates("templates/footer.php", array());



?>