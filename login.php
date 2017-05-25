<?php

include ('autoload.php');

if (isset($_POST["send"])) {
    $loginFormFilds = ['email', 'password'];
    $form_item = array();
    $error = array();
    //проверка на правильность заполнения полей
    foreach ($loginFormFilds as $key) {
        if (!empty($_POST[$key])) {
            $form_item[$key] = htmlspecialchars($_POST[$key]);
        } else {
            $error[$key] = "Не заполнено";
        }
    }
    if (!$error) {
        $user = Authorization::authorize($form_item["email"], $form_item["password"]);
        if ($user === true) {
            //если пароль верен, отправляем пользователя на главную стр
            header("Location: /index.php");
            exit();
        } else {
            $error = $user;
        }
    }
    $data["error"] = $error;
} else {
    $data = array ("error" => array());
}

//получение всех категорий

$data_footer["categories_equipment"] = Category::getAll();

echo Templates::render("templates/header.php", array());
echo Templates::render("templates/main-login.php", $data);
echo Templates::render("templates/footer.php", $data_footer);

?>
