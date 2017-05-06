<?php
include ('functions.php');
session_start();

$header_data['username'] = requireAuthentication();

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

    //в зависимости от выполнения условий в if, подключаем разные шаблоны
    $data = array (
        "categories_equipment" => getCategories(),
        "lot_time_remaining" => getLotTimeRemaining(),
    );

    echo connectTemplates("templates/header.php", $header_data);
    if ($error) {
        $data["error"] = $error;        
        $data["lot_item"] = $lot_item;
        echo connectTemplates("templates/form.php", $data);
    }
    else {
        $lot_item["category"] = getCategories()[$lot_item["category"]-1] ;
        $lot_item["title"] = $lot_item["lot-name"];
        $lot_item["id"] = 6;
        $data["announcement_list"] = array ( $lot_item);
        echo connectTemplates("templates/main.php", $data);
    }
    echo connectTemplates("templates/footer.php", array());

} else {
    $data = array (
        "error"=>array(),
        "lot_item" => array()
    );
    echo connectTemplates("templates/header.php", $header_data);
    echo connectTemplates("templates/form.php", $data);
    echo connectTemplates("templates/footer.php", array());
}
?>