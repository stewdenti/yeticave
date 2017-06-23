<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link href="/css/normalize.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>

<header class="main-header">
    <div class="main-header__container container">
        <h1 class="visually-hidden">YetiCave</h1>
        <a class="main-header__logo" href="/main">
            <img src="/img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
        </a>
        <form class="main-header__search" method="get" action="/main/find">
            <input type="search" name="search" placeholder="Поиск лота">
            <input class="main-header__search-btn" type="submit" name="find" value="Найти">
        </form>
        <a class="main-header__add-lot button" href="/lot/new">Добавить лот</a>

        <nav class="user-menu">
            <?php if (!empty($user)): ?>
            <div class="user-menu__image">
                <img src="<?=$user->avatar_img?>" width="40" height="40" alt="Пользователь">
            </div>
            <div class="user-menu__logged">
                <p><a href="/user/mylots"><?=$user->name?></a></p>
                <a href="/user/signout">Выйти</a>
            </div>
        <?php else: ?>
            <ul class="user-menu__list">
                <li class="user-menu__item">
                    <a href="/user/signup">Регистрация</a>
                </li>
                <li class="user-menu__item">
                    <a href="/user/signin">Вход</a>
                </li>
            </ul>
        </nav>
        <?php endif;?>

    </div>
</header>
