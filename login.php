<?php
include ('functions.php');



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
        if (!$link = create_connect()) {
            echo mysqli_connect_errno();
            exit ();
        }

        $sql = "SELECT * FROM users WHERE email=?;";


        if ($user = dataRetrieval($link,$sql,[$form_item["email"]])) { //поиск пользователя по email

            if (password_verify($form_item["password"], $user[0][2])) {//сравнение пароля с хешом пароля в массиве
                session_start();
                $_SESSION['user_name'] = $user[0][3];
                $_SESSION["user_email"] = $user[0][1];
                $_SESSION["user_id"] = $user[0][0];
                $_SESSION["avatar_img"] = $user[0][5];

                header("Location: /index.php");//если пароль верен, отправляем пользователя на главную стр
                exit();
            } else {
                $error["wrong_password"] = "Вы ввели не верный пароль";
                $error["password"] = True;
            }
        } else {
            $error["wrong_username"] = "Пользователья с такием email не существует";
            $error["email"] = True;
        }

    }
    $data["error"] = $error;
} else {
    $data = array ("error"=>array());
}
if (!$link = create_connect()) {
    echo mysqli_connect_errno();
    exit ();
}
//получение всех категорий
$categories = getCategories($link);
$data_footer["categories_equipment"] = $categories;

echo connectTemplates("templates/header.php", array());
echo connectTemplates("templates/main-login.php", $data);
echo connectTemplates("templates/footer.php", $data_footer);


?>