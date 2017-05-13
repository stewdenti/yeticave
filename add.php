<?php
include ('functions.php');
session_start();

$header_data = requireAuthentication();

if (!$link = create_connect()) {
    echo mysqli_connect_errno();
    exit ();
}

$categories = getCategories($link);

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

    if (strtotime($lot_item["lot-date"]) < time()) {
        $error["lot-date"] = "Неверно задана дата окончания";
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
        $sql = "INSERT lots SET 
user_id = ?,
category_id=?,
name=?,
description=?,
img_path=?,
start_price=?,
step=?,
end_date=?,
add_date=NOW(),
winner=0
";

    $lot_id = dataInsertion($link, $sql, [
        $_SESSION["user_id"],
        $lot_item["category"],
        $lot_item["lot-name"],
        $lot_item["message"],
        $lot_item["URL-img"],
        $lot_item["price"],
        $lot_item["lot-step"],
        date("Y:m:d H:i",strtotime($lot_item["lot-date"]))
    ]);
    $sql = " INSERT binds SET
    user_id=?,
    lot_id=?,
    price=?,
    date=NOW()    
    ";
    $result = dataInsertion($link,$sql,[
        $_SESSION["user_id"], $lot_id, $lot_item["price"]
    ]);

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