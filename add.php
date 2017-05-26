<?php
include ('autoload.php');
session_start();

Authorization::blockAccess();

$user_data = Authorization::getAuthData();
$header_data["user"] = $user_data;

$categories = CategoryFinder::getAll();
$data ["categories_equipment"] = $categories;
$data_footer["categories_equipment"] = $categories;

if (isset($_POST["send"])) {
    $lot_item = array();
    $error = array();
    //проверяем значения глобального массива, куда ушли данные формы после отправки

    $expectedPostData = ['name', 'description', 'category_id', 'end_date', 'start_price', 'step'];
    $expectedNumericFields = ['start_price', 'step'];

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

    $file = $_FILES["img_path"];
    //Проверяем принят ли файл
    if (file_exists($file['tmp_name'])) {
        $info = @getimagesize($file['tmp_name']);
        if (preg_match('{image/(.*)}is', $info["mime"], $p)) {
            $name = "img/".time().".".$p[1];//делаем имя равным текущему времени в секундах
            move_uploaded_file($file['tmp_name'], $name);//добавляем файл в папку
            $lot_item["img_path"] = $name;//путь до папки
        } else {
            $error["img_path"] = "Попытка добавить файл недопустимого формата";
        }
    } else {
        $error["img_path"] = "файл не выбран";
    }

    if (!empty($lot_item["end_date"]) && strtotime($lot_item["end_date"]) < time()) {
        $error["end_date"] = "Время оконачания лота задано в прошлом";
    }

    //в зависимости от выполнения условий в if, подключаем разные шаблоны
    $data = array (
        "categories_equipment" => $categories,
    );

    if ($error) {
        $data["error"] = $error;
        $data["lot_item"] = $lot_item;
        echo Templates::render("templates/header.php", $header_data);
        echo Templates::render("templates/form.php", $data);
        echo Templates::render("templates/footer.php", $data_footer);
    }
    else {
        $lot_item["user_id"] = $user_data->id;
        $lot_item["add_date"] = date("Y:m:d H:i:s");
        $lot_item["end_date"] = date("Y:m:d H:i", strtotime($data["end_date"]));
        $l = new Lot($lot_item);
        $l->insert();
        header("Location: /lot.php?id=".$l->id);
        exit();
    }
} else {
    $data = array (
        "error" => array(),
        "lot_item" => array(),
        "categories_equipment" => $categories,
    );
    echo Templates::render("templates/header.php", $header_data);
    echo Templates::render("templates/form.php", $data);
    echo Templates::render("templates/footer.php", $data_footer);
}

?>
