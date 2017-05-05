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

$lot_item = "";
//цикл поиск запрошенного лота
foreach ($announcement_list as $key => $value) {
    if ($value["id"] == $_REQUEST["id"]) {
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


/**
 * @param $time
 */

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
        $maxBet = getMaxBet($bets);
        if (!is_numeric($form_item['cost'])) {
            $error['cost'] = "Заполните ставку в виде числа";
        } else if ((int)$form_item['cost'] < $maxBet) {
            $error['cost'] = "Ставка должна быть больше ".$maxBet;
        } else {
            $form_item['time'] = $time;

            if (isset($_COOKIE["lot_bind"])) {
                $serelized_lot_item = $_COOKIE["lot_bind"];
                $lot_bind = json_decode($serelized_lot_item, true);
                $lot_bind[$form_item["id"]] = array("cost" => $form_item["cost"],"time" => $form_item["time"]);
                $serelized_form_item = json_encode( $lot_bind);
                setcookie('lot_bind', $serelized_form_item, strtotime("+30 days"));
            } else {
                $lot_bind[$form_item["id"]] = array("cost" => $form_item["cost"],"time" => $form_item["time"]);
                $serelized_form_item = json_encode($lot_bind);
                setcookie('lot_bind', $serelized_form_item, strtotime("+30 days"));
            }



            header("Location: /mylots.php");
            exit();
        }


    }
    if ($error) {
        $data['error'] = $error;
        echo connectTemplates("templates/header.php", $header_data);
        echo connectTemplates("templates/main-lot.php", $data);
        echo connectTemplates("templates/footer.php", array());
    }

} else {
    if (isset($_COOKIE["lot_bind"])) {
        $serel_lot_item = $_COOKIE["lot_bind"];
        $lot_bind = json_decode($serel_lot_item, true);
        foreach ($lot_bind as $key => $value) {
            if ($key == $_REQUEST["id"] ) {
                $data["bind_done"] = true;
                break;
            }
        }
    }
    $data ["error"] = array();

    echo connectTemplates("templates/header.php", $header_data);
    echo connectTemplates("templates/main-lot.php", $data);
    echo connectTemplates("templates/footer.php", array());
}
?>




