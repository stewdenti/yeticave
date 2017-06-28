<form class="form container <?php if ($error):?>form--invalid<?php endif; ?>" action="/registration.php" method="post" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php printInvalidItemClass($error, 'email'); ?>">    <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php printInputItemValue($form_item, 'email');?>">
        <span class="form__error"><?php if (isset($error["email"])) {print($error["email"]); } ?></span>
    </div>
    <div class="form__item <?php printInvalidItemClass($error, 'password'); ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <span class="form__error"><?php if (isset($error["password"])) {print($error["password"]); } ?></span>
    </div>
    <div class="form__item <?php printInvalidItemClass($error, 'name'); ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?php printInputItemValue($form_item, 'name');?>">
        <span class="form__error"><?php if (isset($error["name"])) {print($error["name"]); } ?></span>
    </div>
    <div class="form__item <?php printInvalidItemClass($error, 'contacts'); ?>">
        <label for="contacts">Контактные данные*</label>
        <textarea id="contacts" name="contacts" placeholder="Напишите как с вами связаться"><?php printInputItemValue($form_item, 'contacts');?></textarea>
        <span class="form__error"><?php if (isset($error["contacts"])) {print($error["contacts"]); } ?></span>
    </div>
    <div class="form__item form__item--file form__item--last <?php printInvalidItemClass($error, 'avatar_img'); ?>">
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="avatar_img" value="<?php printInputItemValue($form_item, 'avatar_img');?>">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error"><?php if (isset($error["avatar_img"])) {print($error["avatar_img"]); } ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="RegForm">Зарегистрироваться</button>
    <a class="text-link" href="/user/signin">Уже есть аккаунт</a>
</form>
