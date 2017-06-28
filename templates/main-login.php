<form class="form container <?php if (!empty($error)):?>form--invalid<?php endif; ?>" action="" method="post">
    <h2>Вход: <?php if (isset($w)):?>Теперь вы можете войти, используя свой email и пароль<?php elseif (isset($error["wrong_message"])): ?> <?=$error["wrong_message"]?> <? endif; ?></h2>
    <div class="form__item <?php printInvalidItemClass($error, 'email'); ?>">    <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail">
        <?php if (isset($error["email"])): ?>
        <span class="form__error"><?=$error["email"]?></span>
        <?php endif; ?>
    </div>
    <div class="form__item form__item--last <?php printInvalidItemClass($error, 'password'); ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <?php if (isset($error["password"])): ?>
        <span class="form__error"><?=$error["password"]?></span>
        <?php endif; ?>
    </div>
    <button type="submit" name="AuthForm" value="true" class="button">Войти</button>
</form>
