<?php

    function printInvalidItemClass($errors, $name)
    {
        if (isset($errors[$name])) {
            echo "form__item--invalid";
        }
    }
function printInputItemValue($item, $name)
{
    if (!empty($item[$name])) {
        echo $item[$name];
    }
}
?>
<form class="form form--add-lot container <?php if ($error):?>form--invalid<?php endif; ?>" action="/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php printInvalidItemClass($error, 'lot-name'); ?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?php printInputItemValue($lot_item, 'lot-name');?>">
            <span class="form__error"><?php if (isset($error["lot-name"])) {print($error["lot-name"]); } ?></span>
        </div>
        <div class="form__item <?php printInvalidItemClass($error, 'category'); ?>">
            <label for="category">Категория</label>
            <select id="category" name="category">
                <option value="">Выберите категорию</option>
                <?php foreach (getCategories() as $key => $value) {
                    if (isset($lot_item['category']) && $lot_item['category']-1==$key) {
                        echo "<option value=\"".($key+1)."\" selected>".$value."</option>";
                    } else {
                        echo "<option value=\"".($key+1)."\">".$value."</option>";
                    }
                } ?>
            </select>
            <span class="form__error"><?php if (isset($error["category"])) {print($error["category"]); } ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php printInvalidItemClass($error, 'message'); ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" ><?php printInputItemValue($lot_item, 'message');?></textarea>
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
            <input class="visually-hidden"  type="file" id="photo2" name="lot-img" value=" ">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?php printInvalidItemClass($error, 'price'); ?>">
            <label for="price">Начальная цена</label>
            <input id="price" type="number" name="price" placeholder="0" value="<?php printInputItemValue($lot_item, 'price');?>">
            <span class="form__error"><?php if (isset($error["price"])) {print($error["price"]); } ?></span>
        </div>
        <div class="form__item form__item--small <?php printInvalidItemClass($error, 'lot-step'); ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" value="<?php printInputItemValue($lot_item, 'lot-step');?>">
            <span class="form__error"><?php if (isset($error["lot-step"])) {print($error["lot-step"]); } ?></span>
        </div>
        <div class="form__item <?php printInvalidItemClass($error, 'lot-date'); ?>">
            <label for="lot-date">Дата заверщения</label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" value="<?php printInputItemValue($lot_item, 'lot-date');?>">
            <span class="form__error"><?php if (isset($error["lot-date"])) {print($error["lot-date"]); } ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="send">Добавить лот</button>
</form>