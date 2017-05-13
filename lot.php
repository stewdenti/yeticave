<?php
include ('functions.php');

// установка и проверка устновки соединения с бд

if (!$link = create_connect()) {
    echo mysqli_connect_errno();
    exit ();
}
//получение всех категорий
$categories = getCategories($link);
$data["categories_equipment"] = $categories;
$data_footer["categories_equipment"] = $categories;


session_start();

if (isset($_SESSION["user_name"])) {
    $header_data = array ("username"=>$_SESSION["user_name"],
        "avatar" => $_SESSION["avatar_img"]);
    $data["username"] = $_SESSION["user_name"];


} else {
    $header_data = array();
}


// проверка пришел ли id лота и получение данных о лоте  из базы
$lot_item_list = "";
$bets = "";

if (!empty($_REQUEST["id"]) && is_numeric($_REQUEST["id"])) {
    $sql = "SELECT lots.id, lots.`name`, `img_path`, `categories`.`name` AS 'category', MAX( binds.`price`) AS price,
description, step, start_price, end_date
FROM lots
JOIN `categories`
ON lots.`category_id` = `categories`.id
JOIN binds
ON lots.id = binds.`lot_id`
WHERE lots.id = ?
GROUP BY lots.id";
    $lot_item_list = dataRetrieval($link, $sql, [$_REQUEST['id']]);
}


if ($lot_item_list == "") {
    header("HTTP/1.1 404 Not Found");
    echo "<h1>404 Страница не найдена</h1>";
    exit ();
} else {
    //подготовка данных их базы для шаблона.
    $lot_item = [];
    $template_fields =["id","name","URL-img","category","price","description","step","start_price","end_date"];
    $i = 0;
    foreach ($template_fields as $value) {
        $lot_item[$value] = $lot_item_list[0][$i];
        $i++;
    }

    $data["lot_item"] = $lot_item;
    //Получение данных о ставках для лота из базы
    $sql = "SELECT users.name AS name, binds.price, binds.date FROM binds 
JOIN users ON binds.user_id=users.id 
JOIN lots ON lots.id = binds.lot_id
WHERE binds.lot_id=? AND binds.price != lots.start_price
ORDER BY price DESC";
    $bets = dataRetrieval($link, $sql, [$_REQUEST['id']]);

    $data["bets"] = $bets;
}


if (isset($_POST["send"])) {
    $time  = time();
    $lotFields = ['cost', 'id'];
    $error = [];
    $form_item = [];
    foreach ($lotFields as $key) {
        if (!empty($_POST[$key]) || $_POST[$key]=== "0") {
            $form_item[$key] = htmlspecialchars($_POST[$key]);
        } else {
            $error[$key] = "Заполните ставку";
        }
    }
    if (!$error) {
        $maxBet = getMaxBet($link, $form_item["id"]);

        if (!is_numeric($form_item['cost'])) {
            $error['cost'] = "Заполните ставку в виде числа";
        } else if ((int)$form_item['cost'] < $maxBet) {
            $error['cost'] = "Ставка должна быть больше ".$maxBet;
        } else {

            $sql = "INSERT binds SET user_id=?, lot_id=?, price=?, date=NOW();";
            $result = dataInsertion($link, $sql, [$_SESSION["user_id"], $form_item["id"], $form_item["cost"]]);

            header("Location: /mylots.php");
        }
    }
    if ($error) {
        $data['error'] = $error;
        echo connectTemplates("templates/header.php", $header_data);
        echo connectTemplates("templates/main-lot.php", $data);
        echo connectTemplates("templates/footer.php", $data_footer);
    }

} else {

    if (isset($_SESSION["user_id"])) {
        $sql = "SELECT COUNT(price) AS number FROM binds 
JOIN lots ON binds.lot_id=lots.id 
WHERE lot_id=? AND binds.user_id=? AND binds.price!=lots.start_price";
        $res = dataRetrieval($link, $sql, [ $_REQUEST['id'], $_SESSION["user_id"] ]);
    } else {
        $sql = "SELECT COUNT(price) AS number FROM binds 
JOIN lots ON binds.lot_id=lots.id 
WHERE lot_id=? AND binds.price!=lots.start_price";
        $res = dataRetrieval($link, $sql, [ $_REQUEST['id']]);
    }


    if ($res[0][0] > 0 ) {
        $data["bind_done"] = true;
    }

    $data ["error"] = array();

    echo connectTemplates("templates/header.php", $header_data);
    echo connectTemplates("templates/main-lot.php", $data);
    echo connectTemplates("templates/footer.php", $data_footer);
}


    ?>