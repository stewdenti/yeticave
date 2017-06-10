<?php

include ('autoload.php');

if (isset($_POST["AuthForm"])) {
    $form = AuthForm::getFormData();
    if ($form->isValid()) {
        $user = Authorization::authorize($form->email, $form->password);
        if ($user === true) {
            //если пароль верен, отправляем пользователя на главную стр
            header("Location: /index.php");
            exit();
        } else {
            $data["error"] = $user;
        }
    } else {
        $data["error"]= $form->getErrors();
    }
} else {
    $data = array ("error" => array());
}

//получение всех категорий
if (isset($_REQUEST["welcome"])){
    $data["w"] = true;
}
$data_footer["categories_equipment"] = CategoryFinder::getAll();

echo Templates::render("templates/header.php", array());
echo Templates::render("templates/main-login.php", $data);
echo Templates::render("templates/footer.php", $data_footer);
