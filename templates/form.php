
<form class="form form--add-lot container<?php if ($error):?>form--invalid<?php endif; ?>" action="/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php if (isset($error["lot-name"])):?>form__item--invalid<?php endif; ?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота">
            <span class="form__error"><?php if (isset($error["lot-name"])) {print($error["lot-name"]); } ?></span>
        </div>
        <div class="form__item <?php if (isset($error["category"])):?>form__item--invalid<?php endif; ?>">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option>Выберите категорию</option>
                <option>Доски и лыжи</option>
                <option>Крепления</option>
                <option>Ботинки</option>
                <option>Одежда</option>
                <option>Инструменты</option>
                <option>Разное</option>
            </select>
            <span class="form__error"><?php if (isset($error["category"])) {print($error["category"]); } ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php if (isset($error["message"])):?>form__item--invalid<?php endif; ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" ></textarea>
        <span class="form__error"><?php if (isset($error["message"])) {print($error["message"]); } ?></span>
    </div>
    <div class="form__item form__item--file"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden"  type="file" id="photo2" name="lot-img" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?php if (isset($error["lot-rate"])):?>form__item--invalid<?php endif; ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0" >
            <span class="form__error"><?php if (isset($error["lot-rate"])) {print($error["lot-rate"]); } ?></span>
        </div>
        <div class="form__item form__item--small <?php if (isset($error["lot-step"])):?>form__item--invalid<?php endif; ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" >
            <span class="form__error"><?php if (isset($error["lot-step"])) {print($error["lot-step"]); } ?></span>
        </div>
        <div class="form__item <?php if (isset($error["lot-date"])):?>form__item6--invalid<?php endif; ?>">
            <label for="lot-date">Дата заверщения</label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" >
            <span class="form__error"><?php if (isset($error["lot-date"])) {print($error["lot-date"]); } ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="send">Добавить лот</button>
</form>