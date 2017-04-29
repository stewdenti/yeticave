<?php
include ('functions.php');

if (isset($_POST["send"])) {
    $lot_item = array();
    $error = array();
    //проверяем значения глобального массива, куда ушли данные формы после отправки
    if (isset($_POST["lot-name"]) && !empty($_POST["lot-name"])) {
        $lot_item["title"] = htmlspecialchars($_POST["lot-name"]);
    } else {
        $error["lot-name"] = "Заполните это поле";
    }
    if (isset($_POST["message"]) && !empty($_POST["message"])) {
        $lot_item["message"] = htmlspecialchars($_POST["message"]);
    } else {
        $error["message"] = "Заполните это поле";
    }
    if (isset($_POST["category"]) && !empty($_POST["category"])) {
            $lot_item["category"] = htmlspecialchars($_POST["category"]);
    } else {
            $error["category"] = "Выбирите категорию";
    }
    if (isset($_POST["lot-rate"]) && !empty($_POST["lot-rate"]) && is_int((int)$_POST["lot-rate"])) {
        $lot_item["price"] = $_POST["lot-rate"];
    }  else {
        $error["lot-rate"] = "Заполните пожалуйста поле";
    }
    if (isset($_POST["lot-step"]) && !empty($_POST["lot-step"]) && is_int((int)$_POST["lot-step"])) {
        $lot_item["lot-step"] = $_POST["lot-step"];
    }  else {
        $error["lot-step"] = "Заполните пожалуйста поле";
    }
    if (isset($_POST["lot-date"]) && !empty($_POST["lot-date"])) {
        $lot_item["lot-date"] = htmlspecialchars($_POST["lot-date"]);
    }  else {
        $error["lot-date"] = "Заполните пожалуйста поле";
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
    $announcement_list = array ( $lot_item);
    $data = array (
        "categories_equipment" => $categories_equipment,
        "announcement_list"    => $announcement_list,

    );//в зависимости от выполнения условий в if, подключаем разные шаблоны
    if ($error) {
        $data["error"] = $error;
        echo connectTemplates("templates/header.php", array());
        echo connectTemplates("templates/form.php", $data);
        echo connectTemplates("templates/footer.php", array());
    } else {
        echo connectTemplates("templates/header.php", array());
        echo connectTemplates("templates/main.php", $data);
        echo connectTemplates("templates/footer.php", array());
    }
} else {
    echo connectTemplates("templates/header.php", array());
    echo connectTemplates("templates/form.php", array());
    echo connectTemplates("templates/footer.php", array());
    
    
}




?>