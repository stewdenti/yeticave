<form class="form container <?php if (!empty($error)):?>form--invalid<?php endif; ?>" action="/login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?php printInvalidItemClass($error, 'email'); ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail">
        <?php if (isset($error["wrong_username"])): ?>
        <span class="form__error">Такого пользователя не существует</span>
        <?php else: ?>
        <span class="form__error">Введите e-mail</span>
        <?php endif; ?>
    </div>
    <div class="form__item form__item--last <?php printInvalidItemClass($error, 'password'); ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <?php if (isset($error["wrong_password"])): ?>
        <span class="form__error">Вы ввели неверный пароль</span>
        <?php else: ?>
        <span class="form__error">Введите пароль</span>
        <?php endif; ?>
    </div>
    <button type="submit" name="send" value="true" class="button">Войти</button>
</form>