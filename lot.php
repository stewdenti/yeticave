<?php
include ('functions.php');

// установка и проверка устновки соединения с бд
$link = create_connect();
if (!$link) {
    echo mysqli_connect_errno();
    exit ();
}
session_start();
//Заполняем данные для шаблона header
$categories = getAllCategories($link);
$user_data = requireAuthentication();
$header_data = $user_data;
$header_data["categories_equipment"] =$categories;
//заполняем данные для шаблона main
$data = $user_data;
$data["categories_equipment"] = $categories;
//заполняем данные для шаблона footer
$data_footer["categories_equipment"] = $categories;


// проверка пришел ли id лота и получение данных о лоте  из базы
$lot_item = "";
$bets = "";
$lot_id = $_REQUEST["id"];

$data["bind_done"] = false;
if ($user_data) {
    $data["bind_done"] = isMakeBindAllowed($link,$lot_id, $user_data["user_id"]);
}

if (!empty($lot_id) && is_numeric($lot_id)) {
    $lot_item = getLotByKey($link,"id",$lot_id);
    $lot_bets = getBetsByLotID($link,$lot_id);
}

if ($lot_item == "") {
    header("HTTP/1.1 404 Not Found");
    echo "<h1>404 Страница не найдена</h1>";
    exit ();
} else {
    //подготовка данных их базы для шаблона.
    $data["lot_item"] = $lot_item;
    //Получение данных о ставках для лота из базы
    $data["bets"] = $lot_bets;
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
            $data = array (
                "user_id" => $user_data["user_id"],
                "lot_id" => $form_item["id"],
                "cost" => $form_item["cost"]
            );

            $result = addNewBind($link, $data);

            header("Location: /mylots.php");
            exit();
        }
    }
    if ($error) {
        $data['error'] = $error;
        echo connectTemplates("templates/header.php", $header_data);
        echo connectTemplates("templates/main-lot.php", $data);
        echo connectTemplates("templates/footer.php", $data_footer);
    }
    // блок else не нужен, т.к. если никаких ошибок не было найдено, то выполнение скрипта завершится раньше.
} else {
    $data ["error"] = array();

    echo connectTemplates("templates/header.php", $header_data);
    echo connectTemplates("templates/main-lot.php", $data);
    echo connectTemplates("templates/footer.php", $data_footer);
}
    ?>