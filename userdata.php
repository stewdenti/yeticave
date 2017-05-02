<?php
include ('functions.php');

// пользователи для аутентификации
$users = [
    [
        'email' => 'ignat.v@gmail.com',
        'name' => 'Игнат',
        'password' => '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'
    ],
    [
        'email' => 'kitty_93@li.ru',
        'name' => 'Леночка',
        'password' => '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'
    ],
    [
        'email' => 'warrior07@mail.ru',
        'name' => 'Руслан',
        'password' => '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW'
    ]
];
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

        if ($user = searchUserByKey($form_item["email"],"email", $users)) { //поиск пользователя по email

            if (password_verify($form_item["password"], $user['password'])) {//сравнение пароля с хешом пароля в массиве
                session_start();
                $_SESSION['user'] = $user["name"];
                header("Location: /index.php");//если пароль верен, отправляем пользователя на главную стр
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

    echo connectTemplates("templates/header.php", array());
    echo connectTemplates("templates/login.php", $data);
    echo connectTemplates("templates/footer.php", array());

} else {

    $data = array(
        "categories_equipment" => getCategories(),
        "announcement_list" => $announcement_list,
        "lot_time_remaining" => getLotTimeRemaining(),
    );
    echo connectTemplates("templates/header.php", array());
    echo connectTemplates("templates/login.php", $data);
    echo connectTemplates("templates/footer.php", array());
}
