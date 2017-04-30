<?php
include ('functions.php');

if (isset($_POST["send"])) {
    $lot_item = array();
    $error = array();
    //проверяем значения глобального массива, куда ушли данные формы после отправки

    $expectedPostData = ['lot-name', 'message', 'category', 'lot-date'];
    $expectedNumericFields = ['lot-rate', 'lot-step'];

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
        } else {
            $error[$key] = "Здесь может быть только число";
        }
    }
    $imgDir = "img"; //каталог для хранения
    $file = $_FILES["lot-img"];
    //Проверяем принят ли файл
    if (file_exists($file['tmp_name'])) {
        $info = @getimagesize($file['tmp_name']);
        if (preg_match('{image/(.*)}is', $info["mime"], $p)) {
            $name = "img/".time().".".$p[1];//делаем имя равным текущему времени в секундах
            move_uploaded_file($file['tmp_name'], $name);//обавляем файл в папку
            $lot_item["URL-img"] = $name;//путь до папки
        } else {
            $error["lot-img"] = "Попытка добавить файл недопустимого формата";
        }
    } else {
        $error["lot-img"] = "файл не выбран";
    }

    $categories_equipment = array("Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное");

   //в зависимости от выполнения условий в if, подключаем разные шаблоны
    $data = array (
        "categories_equipment" => $categories_equipment,
    );
    echo connectTemplates("templates/header.php", array());
    if ($error) {
        $data["error"] = $error;
        echo connectTemplates("templates/form.php", $data);
    }
    else {
        $data["announcement_list"] = array ( $lot_item);
        echo connectTemplates("templates/main.php", $data);
    }
    echo connectTemplates("templates/footer.php", array());

} else {
    echo connectTemplates("templates/header.php", array());
    echo connectTemplates("templates/form.php", array());
    echo connectTemplates("templates/footer.php", array());
    
    
}

    ?>