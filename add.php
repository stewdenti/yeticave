<?php
include ('functions.php');
session_start();
$user_data = requireAuthentication(true);
$header_data = $user_data;
$link = create_connect();
if (!$link) {
    echo mysqli_connect_errno();
    exit ();
}

$categories = getAllCategories($link);
$data ["categories_equipment"] = $categories;
$data_footer["categories_equipment"] = $categories;

if (isset($_POST["send"])) {
    $lot_item = array();
    $error = array();
    //проверяем значения глобального массива, куда ушли данные формы после отправки

    $expectedPostData = ['lot-name', 'message', 'category', 'lot-date', 'price', 'lot-step'];
    $expectedNumericFields = ['price', 'lot-step'];

    foreach ($expectedPostData as $key) {
        if (!empty($_POST[$key])) {
            $lot_item[$key] = htmlspecialchars($_POST[$key]);
        } else {
            $error[$key] = "Заполните это поле";
        }
    }

    foreach ($expectedNumericFields as $key) {
        if (!empty($_POST[$key]) && is_numeric($_POST[$key])) {
            $lot_item[$key] = $_POST[$key];
        } else if (empty($error[$key])) {
            $error[$key] = "Здесь может быть только число";
        }
    }

    $file = $_FILES["lot-img"];
    //Проверяем принят ли файл
    if (file_exists($file['tmp_name'])) {
        $info = @getimagesize($file['tmp_name']);
        if (preg_match('{image/(.*)}is', $info["mime"], $p)) {
            $name = "img/".time().".".$p[1];//делаем имя равным текущему времени в секундах
            move_uploaded_file($file['tmp_name'], $name);//добавляем файл в папку
            $lot_item["URL-img"] = $name;//путь до папки
        } else {
            $error["lot-img"] = "Попытка добавить файл недопустимого формата";
        }
    } else {
        $error["lot-img"] = "файл не выбран";
    }

    if (!empty($lot_item["lot-date"]) && strtotime($lot_item["lot-date"]) < time()) {
        $error["lot-date"] = "Время оконачания лота задано в прошлом";
    }

    //в зависимости от выполнения условий в if, подключаем разные шаблоны
    $data = array (
        "categories_equipment" => $categories,

    );


    if ($error) {
        $data["error"] = $error;        
        $data["lot_item"] = $lot_item;
        echo connectTemplates("templates/header.php", $header_data);
        echo connectTemplates("templates/form.php", $data);
        echo connectTemplates("templates/footer.php", $data_footer);
    }
    else {

        $lot_item["user_id"] = $user_data["user_id"];
        $lot_id = addNewLot($link,$lot_item);
        header("Location: /lot.php?id=".$lot_id);
    }
} else {
    $data = array (
        "error"=>array(),
        "lot_item" => array(),
        "categories_equipment" => $categories,
    );
    echo connectTemplates("templates/header.php", $header_data);
    echo connectTemplates("templates/form.php", $data);
    echo connectTemplates("templates/footer.php", $data_footer);
}

?>