<?php

include ('autoload.php');

session_start();
//проверка авторизации и получение данных
$user_data = Authorization::getAuthData();

$categories = Category::getAll();
//заполняем данные для шаблона header
$header_data["user"] = $user_data;
$header_data["categories_equipment"] = $categories;
//заполняем данные для шаблона main
$data["user"] = $user_data;
$data["categories_equipment"] = $categories;
//заполняем данные для шаблона footer
$data_footer["categories_equipment"] = $categories;


// проверка пришел ли id лота и получение данных о лоте  из базы
$lot_item = null;
$lot_bets = [];
$bets = null;
$lot_id = protectXSS($_REQUEST["id"]);

$data["can_make_bet"] = false;

if ($user_data) {
    $data["can_make_bet"] = Bind::canMakeBet($lot_id, $user_data->id);
}

if (!empty($lot_id) && is_numeric($lot_id)) {
    $lot_item = Lot::getById($lot_id);
    $lot_bets = Bind::getByLotID($lot_id);
}

if ($lot_item ===  null) {
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
        if (!empty($_POST[$key]) || $_POST[$key] === "0") {
            $form_item[$key] = htmlspecialchars($_POST[$key]);
        } else {
            $error[$key] = "Заполните ставку";
        }
    }
    if (!$error) {
        $maxBet = Bind::getMax($form_item["id"]);
        if (!is_numeric($form_item['cost'])) {
            $error['cost'] = "Заполните ставку в виде числа";
        } else if ((int)$form_item['cost'] < $maxBet) {
            $error['cost'] = "Ставка должна быть больше ".$maxBet;
        } else {
            $data = array (
                "user_id" => $user_data->id,
                "lot_id" => $form_item["id"],
                "cost" => $form_item["cost"]
            );

            $result = Bind::addNew($data);

            header("Location: /mylots.php");
            exit();
        }
    }
    if ($error) {
        $data['error'] = $error;
        echo Templates::render("templates/header.php", $header_data);
        echo Templates::render("templates/main-lot.php", $data);
        echo Templates::render("templates/footer.php", $data_footer);
    }
    // блок else не нужен, т.к. если никаких ошибок не было найдено, то выполнение скрипта завершится раньше.
} else {
    $data ["error"] = array();
    echo Templates::render("templates/header.php", $header_data);
    echo Templates::render("templates/main-lot.php", $data);
    echo Templates::render("templates/footer.php", $data_footer);
}

?>
