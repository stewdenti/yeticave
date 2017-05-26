<?php

include ('autoload.php');

$data_footer["categories_equipment"] = CategoryFinder::getAll();

if (isset($_POST["send"])) {
    $segnFormFilds = ['email', 'password', 'name', 'contacts'];
    $form_item = array();
    $error = array();
    // проверяем значения глобального массива, куда ушли данные формы после отправки
    foreach ($segnFormFilds as $key) {
        if (!empty($_POST[$key])) {
            if ($key == "password") {
                $form_item[$key] = password_hash($_POST[$key],PASSWORD_BCRYPT);
            } else {
                $form_item[$key] = htmlspecialchars($_POST[$key]);
            }
        } else {
            $error[$key] = "Заполните это поле";
        }
    }
    if (isset($form_item["email"]) && UserFinder::findByEmail($form_item["email"])) {
        $error["email"] = "Пользователь с таким email уже существует";

    }
    if (!empty($_FILES["avatar_img"]["name"])) {
        $file = $_FILES["avatar_img"];
        //Проверяем принят ли файл
        if (file_exists($file['tmp_name'])) {
            $info = @getimagesize($file['tmp_name']);
            if (preg_match('{image/(.*)}is', $info["mime"], $p)) {
                $name = "img/" . time() . "." . $p[1];//делаем имя равным текущему времени в секундах
                move_uploaded_file($file['tmp_name'], $name);//добавляем файл в папку
                $form_item["avatar_img"] = $name;//путь до папки
            } else {
                $error["avatar_img"] = "Попытка добавить файл недопустимого формата";
            }
        } else {
            $error["avatar_img"] = "файл не может быть загружен";
        }
    } else {
        $form_item["avatar_img"] = "/img/user.jpg";
    }

    if($error) {
        $data["error"] = $error;
        $data["form_item"] = $form_item;
        echo Templates::render("templates/header.php", array());
        echo Templates::render("templates/registration-main.php", $data);
        echo Templates::render("templates/footer.php", $data_footer);
     } else {
        $user = new User($form_item);
        $user->insert();
        header("Location: /login.php");
        exit();
     }
} else {
    $data = array (
        "error" => array(),
        "form_item" => array(),
    );
    echo Templates::render("templates/header.php", array());
    echo Templates::render("templates/registration-main.php", $data);
    echo Templates::render("templates/footer.php", $data_footer);
}

?>
