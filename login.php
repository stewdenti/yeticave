<?php
include ('functions.php');
$link = create_connect();
if (!$link) {
    echo mysqli_connect_errno();
    exit ();
}

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
        $user = getUserByKey($link,"email",$form_item["email"] );
        if ($user) { //поиск пользователя по email
            if (password_verify($form_item["password"], $user["password"])) {//сравнение пароля с хешом пароля в массиве
                session_start();
                $_SESSION["user"] = $user;
//                $_SESSION['user_name'] = $user["name"];
//                $_SESSION["user_email"] = $user["email"];
//                $_SESSION["user_id"] = $user["id"];
//                $_SESSION["avatar_img"] = $user["avatar_img"];
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

//получение всех категорий
$categories = getAllCategories($link);
$data_footer["categories_equipment"] = $categories;

echo connectTemplates("templates/header.php", array());
echo connectTemplates("templates/main-login.php", $data);
echo connectTemplates("templates/footer.php", $data_footer);

?>